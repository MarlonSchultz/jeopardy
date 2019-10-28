module Main exposing (Msg(..), main, update, view)

import AnswerDecoder exposing (Answer, decodeJson)
import Browser
import Html exposing (Html, div, h1, h2, i, node, span, table, tbody, td, text, th, thead, tr)
import Html.Attributes exposing (class, classList, href, id, rel, style)
import Html.Events exposing (onClick)
import Http exposing (..)
import List.Extra
import Time


main =
    Browser.element
        { init = init
        , update = update
        , subscriptions = subscriptions
        , view = view
        }


type Msg
    = GotJson (Result Http.Error (List Answer))
    | ToggleModal Answer
    | AnswerToggle (Result Http.Error ())
    | RequestBuzzer (Result Http.Error String)
    | RevealAnswer String
    | PollBuzzerSubscription Time.Posix


type Buzzed
    = None
    | Red
    | Green
    | Blue
    | Wrong
    | Yellow


type alias AnsweredAnswers =
    { id : String
    , buzzer : Buzzed
    }



-- MODEL


type RequestResult
    = Failure String
    | Loading
    | Success (List Answer)


type alias Model =
    { requestState : RequestResult
    , chosenAnswer : Answer
    , openModal : Bool
    , revealAnswer : String
    , buzzerColor : Buzzed
    , answeredAnswers : List AnsweredAnswers
    }


init : () -> ( Model, Cmd Msg )
init _ =
    ( Model Loading { id = "1", category = "Nothing", points = "10", answer = "string", question = "whatever" } True "0" None (List.singleton { id = "0", buzzer = None })
    , Http.get
        { url = "http://localhost:8080/gameFiles/devcamp2019.json"
        , expect =
            Http.expectJson GotJson decodeJson
        }
    )


getCategoryFromAnswer : Answer -> String
getCategoryFromAnswer answer =
    answer.category


errorToString : Http.Error -> String
errorToString error =
    case error of
        BadUrl url ->
            "The URL " ++ url ++ " was invalid"

        Timeout ->
            "Unable to reach the server, try again"

        NetworkError ->
            "Unable to reach the server, check your network connection"

        BadStatus 500 ->
            "The server had a problem, try again later"

        BadStatus 400 ->
            "Verify your information and try again"

        BadStatus _ ->
            "Unknown error"

        BadBody errorMessage ->
            errorMessage


getListOfCategories : List Answer -> List String
getListOfCategories list =
    List.map getCategoryFromAnswer list
        |> List.Extra.unique



-- Requests


requestOpenQuestion : Cmd Msg
requestOpenQuestion =
    Http.get
        { url = "http://localhost:8080/openQuestion"
        , expect =
            Http.expectWhatever AnswerToggle
        }


requestCloseQuestion : Cmd Msg
requestCloseQuestion =
    Http.get
        { url = "http://localhost:8080/closeQuestion"
        , expect =
            Http.expectWhatever AnswerToggle
        }


queryBuzzer : Cmd Msg
queryBuzzer =
    Http.get
        { url = "http://localhost:8080/getBuzzer"
        , expect =
            Http.expectString RequestBuzzer
        }



-- UPDATE


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        GotJson result ->
            case result of
                Ok answer ->
                    ( { model | requestState = Success answer }
                    , Cmd.none
                    )

                Err err ->
                    ( { model | requestState = Failure (errorToString err) }, Cmd.none )

        ToggleModal answer ->
            case model.openModal of
                True ->
                    ( { model | chosenAnswer = answer, openModal = not model.openModal }
                    , requestOpenQuestion
                    )

                False ->
                    ( { model | chosenAnswer = answer, openModal = not model.openModal, buzzerColor = None, answeredAnswers = { id = answer.id, buzzer = Wrong } :: model.answeredAnswers }
                    , requestCloseQuestion
                    )

        AnswerToggle _ ->
            ( model, Cmd.none )

        RevealAnswer str ->
            ( { model | revealAnswer = str }, Cmd.none )

        PollBuzzerSubscription _ ->
            ( model, queryBuzzer )

        RequestBuzzer result ->
            case result of
                Ok string ->
                    case string of
                        "red" ->
                            ( { model | buzzerColor = Red }
                            , Cmd.none
                            )

                        "blue" ->
                            ( { model | buzzerColor = Red }
                            , Cmd.none
                            )

                        "yellow" ->
                            ( { model | buzzerColor = Red }
                            , Cmd.none
                            )

                        "green" ->
                            ( { model | buzzerColor = Red }
                            , Cmd.none
                            )

                        _ ->
                            ( { model | buzzerColor = None }
                            , Cmd.none
                            )

                Err err ->
                    ( { model | requestState = Failure (errorToString err) }, Cmd.none )



-- SUBSCRIPTIONS


subscriptions : Model -> Sub Msg
subscriptions model =
    if not model.openModal then
        Time.every 500 PollBuzzerSubscription

    else
        Sub.none



-- VIEW


loadCss : String -> Html Msg
loadCss cssLink =
    node "link"
        [ rel "stylesheet"
        , href cssLink
        ]
        []


headline : Html Msg
headline =
    div []
        [ h1 [ class "light-blue center-align z-depth-1" ]
            [ text "Jeopardy" ]
        ]


tableHead : List String -> Html Msg
tableHead listOfCategories =
    thead []
        [ tr [] (singleTableHead listOfCategories)
        ]


singleTableHead : List String -> List (Html Msg)
singleTableHead listOfCategories =
    List.map
        (\singleCategory ->
            th [] [ text singleCategory ]
        )
        listOfCategories


answerBox : List Answer -> List (Html Msg)
answerBox list =
    List.map getSingleBox list


getSingleBox : Answer -> Html Msg
getSingleBox answer =
    td [ id (getIdFromAnswer answer) ]
        [ div [ class "card blue-grey darken-1", onClick <| ToggleModal answer ]
            [ div
                [ class "card-content white-text" ]
                [ span
                    [ class "card-title" ]
                    [ text (getPointsFromAnswer answer) ]
                ]
            ]
        ]


tableRow : List Answer -> List (Html Msg)
tableRow list =
    getAnswersByPoints list
        |> List.map
            (\singleList ->
                tr []
                    (answerBox singleList)
            )


getAnswersByPoints : List Answer -> List (List Answer)
getAnswersByPoints list =
    getPossiblePoints list
        |> List.map
            (\singlePoints ->
                List.filter (\singleAnswer -> getPointsFromAnswer singleAnswer == singlePoints) list
            )


getPossiblePoints : List Answer -> List String
getPossiblePoints listAnswers =
    List.map getPointsFromAnswer listAnswers
        |> List.Extra.unique


getPointsFromAnswer : Answer -> String
getPointsFromAnswer answer =
    answer.points


getIdFromAnswer : Answer -> String
getIdFromAnswer answer =
    answer.id


modalStructure : Model -> Html Msg
modalStructure { chosenAnswer, openModal, revealAnswer, buzzerColor } =
    div [ class "row" ]
        [ div
            [ classList [ ( "col", True ), ( "s8", True ), ( "hoverable", True ), ( "pinned", True ), ( "pull-m2", True ), ( "hide", openModal ) ], style "z-index" "1003" ]
            [ div
                [ classList
                    [ ( "blue-grey", buzzerColor == None )
                    , ( "blue", buzzerColor == Blue )
                    , ( "red", buzzerColor == Red )
                    , ( "yellow", buzzerColor == Yellow )
                    , ( "green", buzzerColor == Green )
                    ]
                , class "card lighten-2"
                ]
                [ div
                    [ class "card-content white-text center-align", id "buzzerColour" ]
                    [ div
                        [ class "card-title" ]
                        [ h2 [] [ text chosenAnswer.answer ] ]
                    , div
                        [ classList [ ( "hide", chosenAnswer.id /= revealAnswer ) ] ]
                        [ h2 [] [ text chosenAnswer.question ] ]
                    , div
                        [ id "countdown" ]
                        []
                    ]
                ]
            , div
                [ class "modal-footer" ]
                [ div
                    [ class "card-action center-align" ]
                    [ div
                        [ id "wrong", class "btn-floating red" ]
                        [ i
                            [ class "close material-icons", onClick <| ToggleModal chosenAnswer ]
                            [ text "close" ]
                        ]
                    , div
                        [ id "right", class " btn-floating green" ]
                        [ i
                            [ class "close material-icons", onClick <| ToggleModal chosenAnswer ]
                            [ text "check" ]
                        ]
                    , div
                        [ id "reveal", class " btn-floating grey" ]
                        [ i
                            [ class "close material-icons", onClick <| RevealAnswer chosenAnswer.id ]
                            [ text "search" ]
                        ]
                    ]
                ]
            ]
        ]


view : Model -> Html Msg
view model =
    case model.requestState of
        Failure err ->
            div []
                [ loadCss "http://localhost:8080/css/elm/materialize.min.css"
                , text ("error" ++ err)
                ]

        Loading ->
            div []
                [ loadCss "http://localhost:8080/css/elm/materialize.min.css"
                , text "Loading"
                ]

        Success jsonDecoded ->
            div []
                [ loadCss "http://localhost:8080/css/elm/materialize.min.css"
                , loadCss "http://localhost:8080/css/elm/jeopardy.css"
                , div [ class "container" ]
                    [ headline
                    , modalStructure model
                    , table [ class "highlight centered fixed" ]
                        [ tableHead (getListOfCategories jsonDecoded)
                        , tbody []
                            (tableRow jsonDecoded)
                        ]
                    ]
                ]

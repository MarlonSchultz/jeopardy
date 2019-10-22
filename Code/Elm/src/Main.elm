module Main exposing (Msg(..), main, update, view)

import AnswerDecoder exposing (Answer, decodeJson)
import Browser
import Html exposing (Html, div, h1, i, node, span, table, tbody, td, text, th, thead, tr)
import Html.Attributes exposing (class, href, id, rel, style)
import Html.Events exposing (onClick)
import Http exposing (..)
import List.Extra
import Maybe exposing (withDefault)


main =
    Browser.element
        { init = init
        , update = update
        , subscriptions = subscriptions
        , view = view
        }


type Msg
    = GotJson (Result Http.Error (List Answer))
    | OpenModal Answer



-- MODEL


type RequestResult
    = Failure String
    | Loading
    | Success (List Answer)


type alias Model =
    { requestState : RequestResult
    , chosenAnswer : Answer
    }


init : () -> ( Model, Cmd Msg )
init _ =
    ( Model Loading { id = "1", category = "Nothing", points = "10", answer = "string", question = "whatever" }
    , Http.get
        { url = "http://localhost:8080/api/getAllAnswers"
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

        OpenModal answer ->
            ( { model | chosenAnswer = answer }, Cmd.none )



-- SUBSCRIPTIONS


subscriptions : Model -> Sub Msg
subscriptions model =
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
        [ div [ class "card blue-grey darken-1", onClick <| OpenModal answer ]
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
    let
        answersByPoints =
            getAnswersByPoints list
    in
    List.map
        (\singleList ->
            tr []
                (answerBox singleList)
        )
        answersByPoints


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


modalStructure : Answer -> Html Msg
modalStructure answer =
    div
        [ id "modal1", class "modal1", style "z-index" "1003" ]
        [ div
            [ class "modal-content" ]
            [ div
                [ class "card blue-grey lighten-2" ]
                [ div
                    [ class "card-content white-text center-align", id "buzzerColour" ]
                    [ div
                        [ class "card-title", id "answer" ]
                        [ text answer.answer ]
                    , div
                        [ id "question" ]
                        [ text answer.question ]
                    , div
                        [ id "countdown" ]
                        []
                    ]
                ]
            ]
        , div
            [ class "modal-footer" ]
            [ div
                [ class "card-action center-align" ]
                [ div
                    [ id "wrong", class "btn-floating red" ]
                    [ i
                        [ class "close material-icons" ]
                        [ text "close" ]
                    ]
                , div
                    [ id "right", class " btn-floating green" ]
                    [ i
                        [ class "close material-icons" ]
                        [ text "check" ]
                    ]
                , div
                    [ id "reveal", class " btn-floating grey" ]
                    [ i
                        [ class "close material-icons" ]
                        [ text "search" ]
                    ]
                , div
                    [ id "close", class "waves-effect waves-green btn-flat" ]
                    [ i
                        [ class "close material-icons" ]
                        [ text "close" ]
                    ]
                ]
            ]
        ]


view : Model -> Html Msg
view model =
    case model.requestState of
        Failure err ->
            div []
                [ loadCss "stylesheets/materialize/css/materialize.min.css"
                , text ("error" ++ err)
                ]

        Loading ->
            div []
                [ loadCss "stylesheets/materialize/css/materialize.min.css"
                , text "Loading"
                ]

        Success jsonDecoded ->
            div []
                [ loadCss "stylesheets/materialize/css/materialize.min.css"
                , loadCss "stylesheets/jeopardy.css"
                , div [ class "container" ]
                    [ headline
                    , modalStructure model.chosenAnswer
                    , table [ class "highlight centered fixed" ]
                        [ tableHead (getListOfCategories jsonDecoded)
                        , tbody []
                            (tableRow jsonDecoded)
                        ]
                    ]
                ]

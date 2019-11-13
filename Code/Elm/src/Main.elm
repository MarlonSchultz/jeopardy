module Main exposing (Msg(..), main, update, view)

import Browser
import Html exposing (Html, div, h1, h2, i, node, span, table, tbody, td, text, th, thead, tr)
import Html.Attributes exposing (class, classList, href, id, rel, style)
import Html.Events exposing (onClick)
import Http exposing (..)
import Json.Decode as JD exposing (field, int, string)
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
    | ToggleModal AnswerContent
    | AnswerToggle (Result Http.Error ())
    | RequestBuzzer (Result Http.Error String)
    | RevealAnswer Int
    | PollBuzzerSubscription Time.Posix
    | SetAnswerToWrong AnswerContent
    | SetAnswerToCorrect AnswerContent


type Buzzed
    = None
    | Red
    | Green
    | Blue
    | Yellow
    | NotSolved


type Answer
    = Wrong AnswerContent
    | Correct AnswerContent
    | NotAnswered AnswerContent


type alias AnswerContent =
    { id : Int
    , points : Int
    , answer : String
    , question : String
    , category : String
    }


type RequestResult
    = Failure String
    | Loading
    | Success (List Answer)


type alias Model =
    { requestState : RequestResult
    , chosenAnswer : AnswerContent
    , openModal : Bool
    , revealAnswer : Int
    , buzzerColor : Buzzed
    }


init : () -> ( Model, Cmd Msg )
init _ =
    ( Model Loading createInitialAnswer False 0 None
    , Http.get
        { url = "http://localhost:8080/gameFiles/devcamp2019.json"
        , expect =
            Http.expectJson GotJson decodeJson
        }
    )


createInitialAnswer : AnswerContent
createInitialAnswer =
    { id = 1, category = "Nothing", points = 10, answer = "string", question = "whatever" }



-- Decoder


answerDecoder : JD.Decoder Answer
answerDecoder =
    JD.map NotAnswered <|
        JD.map5
            AnswerContent
            (field "id" int)
            (field "points" int)
            (field "answer" string)
            (field "question" string)
            (field "category" string)


decodeJson : JD.Decoder (List Answer)
decodeJson =
    JD.list answerDecoder


getCategoryFromAnswer : Answer -> String
getCategoryFromAnswer answer =
    case answer of
        NotAnswered answerConfig ->
            answerConfig.category

        Wrong answerConfig ->
            answerConfig.category

        Correct answerConfig ->
            answerConfig.category


setAnswerState : Model -> AnswerContent -> Bool -> Model
setAnswerState model answerContent setToWrong =
    case model.requestState of
        Success jsonData ->
            let
                newList =
                    List.map
                        (\singleRecord ->
                            case singleRecord of
                                Correct data ->
                                    if answerContent.id == data.id then
                                        if setToWrong then
                                            Wrong data

                                        else
                                            Correct data

                                    else
                                        singleRecord

                                Wrong data ->
                                    if answerContent.id == data.id then
                                        if setToWrong then
                                            Wrong data

                                        else
                                            Correct data

                                    else
                                        singleRecord

                                NotAnswered data ->
                                    if answerContent.id == data.id then
                                        if setToWrong then
                                            Wrong data

                                        else
                                            Correct data

                                    else
                                        singleRecord
                        )
                        jsonData
                        |> Success
            in
            { model | requestState = newList }

        _ ->
            model


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



-- BusinessLogic


toggleModal : Model -> AnswerContent -> Model
toggleModal model answerContent =
    { model | chosenAnswer = answerContent, openModal = not model.openModal, buzzerColor = None }



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
            case not model.openModal of
                True ->
                    ( toggleModal model answer
                    , requestOpenQuestion
                    )

                False ->
                    ( toggleModal model answer
                    , requestCloseQuestion
                    )

        AnswerToggle _ ->
            ( model, Cmd.none )

        RevealAnswer str ->
            ( { model | revealAnswer = str, buzzerColor = NotSolved }, requestCloseQuestion )

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
                            ( { model | buzzerColor = Blue }
                            , Cmd.none
                            )

                        "yellow" ->
                            ( { model | buzzerColor = Yellow }
                            , Cmd.none
                            )

                        "green" ->
                            ( { model | buzzerColor = Green }
                            , Cmd.none
                            )

                        _ ->
                            ( { model | buzzerColor = None }
                            , Cmd.none
                            )

                Err err ->
                    ( { model | requestState = Failure (errorToString err) }, Cmd.none )

        SetAnswerToWrong answerContent ->
            if model.buzzerColor /= None then
                let
                    newModel =
                        toggleModal model answerContent
                in
                ( setAnswerState newModel answerContent True, Cmd.none )

            else
                ( toggleModal model answerContent, requestCloseQuestion )

        SetAnswerToCorrect answerContent ->
            if model.buzzerColor /= None then
                let
                    newModel =
                        toggleModal model answerContent
                in
                ( setAnswerState newModel answerContent False, Cmd.none )

            else
                ( toggleModal model answerContent, requestCloseQuestion )



-- SUBSCRIPTIONS


subscriptions : Model -> Sub Msg
subscriptions model =
    if model.openModal && model.buzzerColor /= NotSolved then
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
    List.map
        (\answer ->
            let
                answerColor =
                    case answer of
                        NotAnswered _ ->
                            "card blue-grey darken-1"

                        Wrong _ ->
                            "card red lighten-1"

                        Correct _ ->
                            "card green darken-3"
            in
            td [ id (String.fromInt (getAnswerConfig answer).id) ]
                [ div [ class answerColor, onClick <| ToggleModal (getAnswerConfig answer) ]
                    [ div
                        [ class "card-content white-text" ]
                        [ span
                            [ class "card-title" ]
                            [ text (String.fromInt (getAnswerConfig answer).points) ]
                        ]
                    ]
                ]
        )
        list


getAnswersByPoints : List Answer -> List (List Answer)
getAnswersByPoints list =
    getPossiblePoints (getAnswerConfigList list)
        |> List.map
            (\singlePoints ->
                List.filter (\singleAnswer -> (getAnswerConfig singleAnswer).points == singlePoints) list
            )


tableRow : List Answer -> List (Html Msg)
tableRow list =
    getAnswersByPoints list
        |> List.map
            (\singleList ->
                tr []
                    (answerBox singleList)
            )


getAnswerConfig : Answer -> AnswerContent
getAnswerConfig answer =
    case answer of
        NotAnswered answerConfig ->
            answerConfig

        Wrong answerConfig ->
            answerConfig

        Correct answerConfig ->
            answerConfig


getAnswerConfigList : List Answer -> List AnswerContent
getAnswerConfigList list =
    List.map
        (\singleAnswer ->
            case singleAnswer of
                NotAnswered answerConfig ->
                    answerConfig

                Wrong answerConfig ->
                    answerConfig

                Correct answerConfig ->
                    answerConfig
        )
        list


getPossiblePoints : List AnswerContent -> List Int
getPossiblePoints listAnswers =
    List.map (\singleAnswer -> singleAnswer.points) listAnswers
        |> List.Extra.unique


modalStructure : Model -> Html Msg
modalStructure { chosenAnswer, openModal, revealAnswer, buzzerColor } =
    div [ class "row" ]
        [ div
            [ classList [ ( "col", True ), ( "s8", True ), ( "hoverable", True ), ( "pinned", True ), ( "pull-m2", True ), ( "hide", not openModal ) ], style "z-index" "1003" ]
            [ div
                [ classList
                    [ ( "blue-grey", buzzerColor == None )
                    , ( "blue-grey", buzzerColor == NotSolved )
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
                            [ class "close material-icons", onClick <| SetAnswerToWrong chosenAnswer ]
                            [ text "close" ]
                        ]
                    , div
                        [ id "right", class " btn-floating green" ]
                        [ i
                            [ class "close material-icons", onClick <| SetAnswerToCorrect chosenAnswer ]
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

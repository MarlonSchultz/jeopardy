module Game exposing (Msg(..))

import Browser
import Html exposing (Html, audio, div, h1, h2, i, node, span, table, tbody, td, text, th, thead, tr)
import Html.Attributes exposing (autoplay, class, classList, controls, href, id, loop, preload, rel, src, style)
import Html.Attributes.Extra exposing (volume)
import Html.Events exposing (onClick)
import Http
import HttpHandler exposing (errorToString)
import Json.Decode as JD exposing (field, int, string)
import List.Extra
import MyUrl exposing (getUrl)
import Svg exposing (rect, svg)
import Svg.Attributes exposing (fill, fillOpacity, height, rx, ry, stroke, strokeWidth, viewBox, width, x, y)
import Time


main : Program () Model Msg
main =
    Browser.element
        { init = init
        , update = update
        , subscriptions = subscriptions
        , view = view
        }


type Msg
    = GotJson (Result Http.Error (List (ExtendedContent AnswerContent)))
    | ToggleModal (ExtendedContent AnswerContent)
    | AnswerToggle (Result Http.Error ())
    | RequestBuzzer (Result Http.Error String)
    | RevealAnswer Int
    | PollBuzzerSubscription Time.Posix
    | SetAnswerToWrong (ExtendedContent AnswerContent)
    | SetAnswerToCorrect (ExtendedContent AnswerContent)
    | SetAnswerToRepeat
    | DecrementTimer Time.Posix


type Buzzed
    = None
    | Red
    | Green
    | Blue
    | Yellow


type alias AnswerContent =
    { id : Int
    , points : Int
    , answer : String
    , question : String
    , category : String
    }


type AnswerType
    = NotAnswered
    | Correct
    | Wrong


type alias ExtendedContent a =
    { a | answered : AnswerType }


type RequestResult
    = Failure String
    | Loading
    | Success


type QuestionsAndAnswers
    = NotLoaded
    | Loaded (List (ExtendedContent AnswerContent))


type alias Model =
    { requestState : RequestResult
    , chosenAnswer : ExtendedContent AnswerContent
    , openModal : Bool
    , revealAnswer : Int
    , buzzerColor : Buzzed
    , volume : Float
    , timerSeconds : Float
    , questionAndAnswers : QuestionsAndAnswers
    }


timerSecondsStartValue : Float
timerSecondsStartValue =
    30


timerSvgLengthInPixels : Float
timerSvgLengthInPixels =
    500


init : () -> ( Model, Cmd Msg )
init _ =
    ( Model Loading createInitialAnswer False 0 None 0.2 timerSecondsStartValue NotLoaded
    , Http.get
        { url = getUrl ++ "/gameFiles/devcamp2019.json"
        , expect =
            Http.expectJson GotJson decodeJson
        }
    )


createInitialAnswer : ExtendedContent AnswerContent
createInitialAnswer =
    { id = 1, category = "Nothing", points = 10, answer = "string", question = "whatever", answered = NotAnswered }



-- Decoder


extendedAnswer : AnswerContent -> ExtendedContent AnswerContent
extendedAnswer answerContent =
    { id = answerContent.id
    , points = answerContent.points
    , answer = answerContent.answer
    , question = answerContent.question
    , category = answerContent.category
    , answered = NotAnswered
    }


answerDecoder : JD.Decoder (ExtendedContent AnswerContent)
answerDecoder =
    JD.map extendedAnswer <|
        JD.map5
            AnswerContent
            (field "id" int)
            (field "points" int)
            (field "answer" string)
            (field "question" string)
            (field "category" string)


decodeJson : JD.Decoder (List (ExtendedContent AnswerContent))
decodeJson =
    JD.list answerDecoder


getCategoryFromAnswer : ExtendedContent AnswerContent -> String
getCategoryFromAnswer answer =
    answer.category


getListOfCategories : QuestionsAndAnswers -> List String
getListOfCategories list =
    case list of
        NotLoaded ->
            [ "NotLoaded" ]

        Loaded answerList ->
            List.map getCategoryFromAnswer answerList
                |> List.Extra.unique



-- Requests


requestOpenQuestion : Cmd Msg
requestOpenQuestion =
    Http.get
        { url = getUrl ++ "/openQuestion"
        , expect =
            Http.expectWhatever AnswerToggle
        }


requestCloseQuestion : Cmd Msg
requestCloseQuestion =
    Http.get
        { url = getUrl ++ "/closeQuestion"
        , expect =
            Http.expectWhatever AnswerToggle
        }


queryBuzzer : Cmd Msg
queryBuzzer =
    Http.get
        { url = getUrl ++ "/getBuzzer"
        , expect =
            Http.expectString RequestBuzzer
        }


setBuzzer : String -> Cmd Msg
setBuzzer buzzer =
    Http.get
        { url = getUrl ++ "/setBuzzer/" ++ buzzer
        , expect =
            Http.expectString RequestBuzzer
        }



-- BusinessLogic


toggleModal : Model -> ExtendedContent AnswerContent -> Model
toggleModal model answerContent =
    let
        newVolume =
            if model.volume == 1 then
                0.2

            else
                1
    in
    { model | chosenAnswer = answerContent, openModal = not model.openModal, buzzerColor = None, volume = newVolume }



-- UPDATE


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        GotJson result ->
            case result of
                Ok content ->
                    ( { model | requestState = Success, questionAndAnswers = Loaded content }
                    , Cmd.none
                    )

                Err err ->
                    ( { model | requestState = Failure (errorToString err) }, Cmd.none )

        ToggleModal answer ->
            if model.openModal == False then
                ( toggleModal model answer
                , requestOpenQuestion
                )

            else
                ( toggleModal model answer
                , requestCloseQuestion
                )

        AnswerToggle _ ->
            ( model, Cmd.none )

        RevealAnswer str ->
            ( { model | revealAnswer = str, buzzerColor = None }, requestCloseQuestion )

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
            setAnswerStatus model answerContent Wrong

        SetAnswerToCorrect answerContent ->
            setAnswerStatus model answerContent Correct

        DecrementTimer _ ->
            if model.timerSeconds > 0 then
                ( { model | timerSeconds = model.timerSeconds - 0.1 }, Cmd.none )

            else if model.timerSeconds <= 0 then
                ( { model | timerSeconds = 0 }, Cmd.none )

            else
                ( model, Cmd.none )

        SetAnswerToRepeat ->
            ( { model | timerSeconds = timerSecondsStartValue }, setBuzzer "none" )


setAnswerStatus : Model -> ExtendedContent AnswerContent -> AnswerType -> ( Model, Cmd Msg )
setAnswerStatus model answerContent answerType =
    if model.buzzerColor /= None then
        let
            newModel =
                toggleModalAndSetAnswerToWrongOrCorrect model answerContent answerType
        in
        ( resetTimerSeconds newModel, requestCloseQuestion )

    else
        ( toggleModal model answerContent, Cmd.none )


resetTimerSeconds : Model -> Model
resetTimerSeconds model =
    { model | timerSeconds = timerSecondsStartValue }


setAnswerState : AnswerType -> ExtendedContent AnswerContent -> Model -> Model
setAnswerState answerCorrectOrWrong clickedAnswerContent model =
    case model.questionAndAnswers of
        NotLoaded ->
            model

        Loaded questions ->
            let
                newAnswerState =
                    List.map
                        (\singleQuestion ->
                            if singleQuestion.id == clickedAnswerContent.id then
                                { clickedAnswerContent | answered = answerCorrectOrWrong }

                            else
                                singleQuestion
                        )
                        questions
            in
            { model | questionAndAnswers = Loaded newAnswerState }


toggleModalAndSetAnswerToWrongOrCorrect : Model -> ExtendedContent AnswerContent -> AnswerType -> Model
toggleModalAndSetAnswerToWrongOrCorrect model answerContent answerIsFalse =
    let
        newModel =
            setAnswerState answerIsFalse answerContent model
    in
    toggleModal newModel answerContent



-- SUBSCRIPTIONS


subscriptions : Model -> Sub Msg
subscriptions model =
    Sub.batch
        [ if model.openModal && model.buzzerColor == None then
            Time.every 500 PollBuzzerSubscription

          else
            Sub.none
        , if model.buzzerColor /= None then
            Time.every 100 DecrementTimer

          else
            Sub.none
        ]



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


answerBox : List (ExtendedContent AnswerContent) -> List (Html Msg)
answerBox list =
    List.map
        (\singleAnswer ->
            let
                answerColor =
                    case singleAnswer.answered of
                        NotAnswered ->
                            "card blue-grey darken-1"

                        Wrong ->
                            "card red lighten-1"

                        Correct ->
                            "card green darken-3"
            in
            td [ id (String.fromInt singleAnswer.id) ]
                [ div [ class answerColor, onClick <| ToggleModal singleAnswer ]
                    [ div
                        [ class "card-content white-text" ]
                        [ span
                            [ class "card-title" ]
                            [ text (String.fromInt singleAnswer.points) ]
                        ]
                    ]
                ]
        )
        list


getRemainingLengthOfTimer : Float -> String
getRemainingLengthOfTimer remainingTime =
    timerSvgLengthInPixels
        * remainingTime
        / 30
        |> String.fromFloat


getAnswersByPoints : List (ExtendedContent AnswerContent) -> List (List (ExtendedContent AnswerContent))
getAnswersByPoints list =
    getPossiblePoints list
        |> List.map
            (\singlePoints ->
                List.filter (\singleAnswer -> singleAnswer.points == singlePoints) list
            )


tableRow : QuestionsAndAnswers -> List (Html Msg)
tableRow list =
    case list of
        NotLoaded ->
            [ div [] [ text "NotLoaded" ] ]

        Loaded listOfAnswers ->
            getAnswersByPoints
                listOfAnswers
                |> List.map
                    (\singleList ->
                        tr []
                            (answerBox singleList)
                    )


getPossiblePoints : List (ExtendedContent AnswerContent) -> List Int
getPossiblePoints listAnswers =
    List.map (\singleAnswer -> singleAnswer.points) listAnswers
        |> List.Extra.unique


getSvgTimer : Float -> Html Msg
getSvgTimer timerSeconds =
    if timerSeconds == 30 then
        Svg.text_ [ x "220", y "30", fill "yellow" ] [ text "Buzz! Do it!" ]

    else
        Svg.text_ [ x "245", y "30", fill "yellow" ] [ text (String.fromInt (round timerSeconds)) ]


modalStructure : Model -> Html Msg
modalStructure { chosenAnswer, openModal, revealAnswer, buzzerColor, timerSeconds } =
    div [ class "row" ]
        [ div
            [ classList [ ( "col", True ), ( "s8", True ), ( "hoverable", True ), ( "pinned", True ), ( "pull-m2", True ), ( "hide", not openModal ) ], style "z-index" "1003" ]
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
                        [ svg
                            [ viewBox "0 0 510 100" ]
                            [ rect [ x "2", y "10", width (String.fromFloat timerSvgLengthInPixels), height "30", rx "15", ry "15", strokeWidth "2", stroke "red", fillOpacity "0" ]
                                []
                            , rect [ x "2", y "10", width (getRemainingLengthOfTimer timerSeconds), height "30", rx "15", ry "15" ]
                                []
                            , getSvgTimer timerSeconds
                            ]
                        ]
                    ]
                ]
            , div
                [ class "modal-footer" ]
                [ div
                    [ class "card-action center-align" ]
                    [ div
                        [ id "ac_unit", class "btn-floating blue" ]
                        [ i
                            [ class "material-icons", onClick <| SetAnswerToRepeat ]
                            [ text "refresh" ]
                        ]
                    , div
                        [ id "wrong", class "btn-floating red" ]
                        [ i
                            [ class "material-icons", onClick <| SetAnswerToWrong chosenAnswer ]
                            [ text "close" ]
                        ]
                    , div
                        [ id "right", class " btn-floating green" ]
                        [ i
                            [ class "material-icons", onClick <| SetAnswerToCorrect chosenAnswer ]
                            [ text "check" ]
                        ]
                    , div
                        [ id "reveal", class " btn-floating grey" ]
                        [ i
                            [ class "material-icons", onClick <| RevealAnswer chosenAnswer.id ]
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
                [ loadCss (getUrl ++ "/css/elm/materialize.min.css")
                , text ("error" ++ err)
                ]

        Loading ->
            div []
                [ loadCss (getUrl ++ "/css/elm/materialize.min.css")
                , text "Loading"
                ]

        Success ->
            div []
                [ loadCss (getUrl ++ "/css/elm/materialize.min.css")
                , loadCss (getUrl ++ "/css/elm/material-design-icons.css")
                , loadCss (getUrl ++ "/css/elm/jeopardy.css")
                , div [ class "container" ]
                    [ headline
                    , modalStructure model
                    , table [ class "highlight centered fixed" ]
                        [ tableHead (getListOfCategories model.questionAndAnswers)
                        , tbody []
                            (tableRow model.questionAndAnswers)
                        ]
                    , div [ style "text-align" "center" ]
                        [ audio [ src (getUrl ++ "/mp3/jeopardy.mp3"), autoplay False, loop True, preload "auto", controls True, volume model.volume ] []
                        ]
                    ]
                ]

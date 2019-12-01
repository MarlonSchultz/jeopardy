module NewType exposing (init, main)

import Browser
import Html exposing (Html, div, text)
import Http
import HttpHandler exposing (errorToString)
import Json.Decode as JD exposing (field, int, string)
import MyUrl exposing (getUrl)


main =
    Browser.element
        { init = init
        , update = update
        , subscriptions = always Sub.none
        , view = view
        }


type Msg
    = GotJson (Result Http.Error (List (ExtendedContent AnswerContent)))


type alias Model =
    { requestState : RequestResult, questionAndAnswers : QuestionsAndAnswers }


type AnswerType
    = NotAnswered
    | Correct
    | Wrong


type QuestionsAndAnswers
    = NotLoaded
    | Loaded (List (ExtendedContent AnswerContent))


type alias AnswerContent =
    { id : Int
    , points : Int
    , answer : String
    , question : String
    , category : String
    }


type alias ExtendedContent a =
    { a | answered : AnswerType }


type RequestResult
    = Failure String
    | Loading
    | Success


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


getInitialData : Cmd Msg
getInitialData =
    Http.get
        { url = getUrl ++ "/gameFiles/devcamp2019.json"
        , expect =
            Http.expectJson GotJson decodeJson
        }


init : () -> ( Model, Cmd Msg )
init _ =
    ( Model Loading NotLoaded, getInitialData )


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        GotJson result ->
            case result of
                Ok jsonFromServer ->
                    ( { model | requestState = Success, questionAndAnswers = Loaded jsonFromServer }
                    , Cmd.none
                    )

                Err err ->
                    ( { model | requestState = Failure (errorToString err) }, Cmd.none )


view : Model -> Html Msg
view model =
    case model.requestState of
        Loading ->
            div [] [ text "Loading" ]

        Failure string ->
            div [] [ text string ]

        Success ->
            div [] [ text "Loaded" ]

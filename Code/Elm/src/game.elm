module GAME exposing (Msg(..), main, update, view)

import Browser
import Html exposing (Html, pre, text)
import Http
import Json.Decode as JD exposing (Decoder, field, int, string)


main =
    Browser.element
        { init = init
        , update = update
        , subscriptions = subscriptions
        , view = view
        }


type Msg
    = GotJson (Result Http.Error String)



-- MODEL


type Model
    = Failure
    | Loading
    | Success String


type alias Answer =
    { points : Int
    , answer : String
    , question : String
    }


init : () -> ( Model, Cmd Msg )
init _ =
    ( Loading
    , Http.get
        { url = "http://localhost:8080/api/getAllAnswers"
        , expect = Http.expectJson GotJson string
        }
    )


answerDecoder : Decoder Answer
answerDecoder =
    JD.map3 Answer
        (field "points" int)
        (field "answer" string)
        (field "question" string)



-- UPDATE


update msg model =
    case msg of
        GotJson result ->
            case result of
                Ok fullText ->
                    ( Success fullText, Cmd.none )

                Err _ ->
                    ( Failure, Cmd.none )



-- SUBSCRIPTIONS


subscriptions : Model -> Sub Msg
subscriptions model =
    Sub.none



-- VIEW


view : Model -> Html Msg
view model =
    case model of
        Failure ->
            text "Couldn't load the json"

        Loading ->
            text "Loading..."

        Success fullText ->
            pre [] [ text fullText ]

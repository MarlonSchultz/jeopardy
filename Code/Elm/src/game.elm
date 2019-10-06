module GAME exposing (Msg(..), main, update, view)

import Browser
import Html exposing (Html, pre, text)
import Http exposing (..)
import Json.Decode as JD exposing (Decoder, field, int, string)


main =
    Browser.element
        { init = init
        , update = update
        , subscriptions = subscriptions
        , view = view
        }


type Msg
    = GotJson (Result Http.Error Answer)



-- MODEL


type Model
    = Failure String
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
        , expect = Http.expectJson GotJson answerDecoder
        }
    )


answerDecoder : Decoder Answer
answerDecoder =
    JD.map3
        Answer
        (field "points" int)
        (field "answer" string)
        (field "question" string)



-- UPDATE


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


update msg model =
    case msg of
        GotJson result ->
            case result of
                Ok fullText ->
                    ( Success fullText.answer, Cmd.none )

                Err err ->
                    ( Failure (errorToString err), Cmd.none )



-- SUBSCRIPTIONS


subscriptions : Model -> Sub Msg
subscriptions model =
    Sub.none



-- VIEW


view : Model -> Html Msg
view model =
    case model of
        Failure err ->
            text err

        Loading ->
            text "Loading..."

        Success fullText ->
            pre [] [ text fullText ]

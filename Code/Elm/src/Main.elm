module Main exposing (Msg(..), main, update, view)

import AnswerDecoder exposing (Answer(..), UnansweredConfig, listOfAnswerDecoder)
import Browser
import Html exposing (Html, div, h1, node, span, table, tbody, td, text, th, thead, tr)
import Html.Attributes exposing (class, href, id, rel)
import Http exposing (..)
import List.Extra


main =
    Browser.element
        { init = init
        , update = update
        , subscriptions = subscriptions
        , view = view
        }


type Msg
    = GotJson (Result Http.Error (List UnansweredConfig))



-- MODEL


type Model
    = Failure String
    | Loading
    | Success (List Answer)


init : () -> ( Model, Cmd Msg )
init _ =
    ( Loading
    , Http.get
        { url = "http://localhost:8080/api/getAllAnswers"
        , expect =
            Http.expectJson GotJson listOfAnswerDecoder
        }
    )


filterByCategory : String -> Answer -> Bool
filterByCategory string answer =
    getCategoryFromAnswer answer == string


getCategoryFromAnswer : Answer -> String
getCategoryFromAnswer answer =
    case answer of
        Answered answered ->
            answered.category

        Unanswered unansweredConfig ->
            unansweredConfig.category


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


getListOfCategoryAsListString : List Answer -> List String
getListOfCategoryAsListString list =
    List.map getCategoryFromAnswer list
        |> List.Extra.unique



-- UPDATE


update msg model =
    case msg of
        GotJson result ->
            case result of
                Ok answer ->
                    ( Success (List.map Unanswered answer)
                    , Cmd.none
                    )

                Err err ->
                    ( Failure (errorToString err), Cmd.none )



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
        [ div [ class "card blue-grey darken-1" ]
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
    case answer of
        Answered answered ->
            answered.points

        Unanswered unansweredConfig ->
            unansweredConfig.points


getIdFromAnswer : Answer -> String
getIdFromAnswer answer =
    case answer of
        Answered answered ->
            answered.id

        Unanswered unansweredConfig ->
            unansweredConfig.id


view : Model -> Html Msg
view model =
    case model of
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
                    , table [ class "highlight centered fixed" ]
                        [ tableHead (getListOfCategoryAsListString jsonDecoded)
                        , tbody []
                            (tableRow jsonDecoded)
                        ]
                    ]
                ]

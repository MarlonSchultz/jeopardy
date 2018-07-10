<?php

/* base.html.twig */
class __TwigTemplate_ea5d3a5b32a794f38b719a9ff27e134cfb1f63d10e7bb30ebbad51ec79002b36 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"utf-8\"/>
    <title>Jeopardy</title>
    <!--Import Google Icon Font-->
    <link href=\"https://fonts.googleapis.com/icon?family=Material+Icons\" rel=\"stylesheet\">
    <!-- Compiled and minified CSS -->
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css\">
    <!--Let browser know website is optimized for mobile-->
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>

    <link rel=\"stylesheet\" href=\"css/jeopardy.css\">
</head>
<body>

<!--Import jQuery before materialize.js-->
<script type=\"text/javascript\" src=\"js/jquery-2.1.1.min.js\"></script>
<!-- Compiled and minified JavaScript -->
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js\"></script>

<script src=\"js/jquery.countdown360.js\"></script>
<script src=\"js/jeopardy.js\"></script>

<div class=\"container\">
    <div><h1 class=\"light-blue center-align z-depth-1\">Jeopardy</h1></div>
    <div class=\"\">
        ";
        // line 28
        $this->displayBlock('content', $context, $blocks);
        // line 29
        echo "    </div>
</div>
</body>
</html>
";
    }

    // line 28
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "base.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  63 => 28,  55 => 29,  53 => 28,  24 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<!DOCTYPE html>
<html>
<head>
    <meta charset=\"utf-8\"/>
    <title>Jeopardy</title>
    <!--Import Google Icon Font-->
    <link href=\"https://fonts.googleapis.com/icon?family=Material+Icons\" rel=\"stylesheet\">
    <!-- Compiled and minified CSS -->
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css\">
    <!--Let browser know website is optimized for mobile-->
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>

    <link rel=\"stylesheet\" href=\"css/jeopardy.css\">
</head>
<body>

<!--Import jQuery before materialize.js-->
<script type=\"text/javascript\" src=\"js/jquery-2.1.1.min.js\"></script>
<!-- Compiled and minified JavaScript -->
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js\"></script>

<script src=\"js/jquery.countdown360.js\"></script>
<script src=\"js/jeopardy.js\"></script>

<div class=\"container\">
    <div><h1 class=\"light-blue center-align z-depth-1\">Jeopardy</h1></div>
    <div class=\"\">
        {% block content %}{% endblock %}
    </div>
</div>
</body>
</html>
", "base.html.twig", "/var/www/jeophpardy/View/base.html.twig");
    }
}

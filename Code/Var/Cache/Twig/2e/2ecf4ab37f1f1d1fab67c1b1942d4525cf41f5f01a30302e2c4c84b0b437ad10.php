<?php

/* jeopardy.html.twig */
class __TwigTemplate_5407cd7f232523f0365bb001d8e185960bd8fc92a34abb2554826ecc0430a5d0 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.html.twig", "jeopardy.html.twig", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_content($context, array $blocks = array())
    {
        // line 3
        echo "    <!-- Modal Structure -->
    <div id=\"modal1\" class=\"modal\">
        <div class=\"modal-content\">
            <div class=\"card teal lighten-2\">
                <div class=\"card-content white-text center-align\">
                    <div class=\"card-title\" id=\"answer\"></div>
                    <div id=\"question\"></div>
                    <div id=\"countdown\"></div>
                </div>
            </div>
        </div>
        <div class=\"modal-footer\">
            <div class=\"card-action center-align\">
                <div id=\"wrong\" class=\"btn-floating red\"><i class=\"close material-icons\" onclick=\"handleAnswer(this, 'wrong')\">close</i></div>
                <div id=\"right\" class=\" btn-floating green\"><i class=\"close material-icons\" onclick=\"handleAnswer(this, 'correct')\">check</i></div>
                <div id=\"close\" class=\"waves-effect waves-green btn-flat\" onclick=\"closeAnswer(this)\"><i class=\"close material-icons\">close</i></div>
            </div>
        </div>
    </div>


    <table class=\"highlight centered\">
        <thead>
        <tr>
            ";
        // line 27
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["jeopardy"] ?? null), "allCategories", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["category"]) {
            // line 28
            echo "                <th>";
            echo twig_escape_filter($this->env, twig_upper_filter($this->env, $context["category"]), "html", null, true);
            echo "</th>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['category'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 30
        echo "        </tr>
        </thead>

        <tbody>
        ";
        // line 34
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["jeopardy"] ?? null));
        foreach ($context['_seq'] as $context["value"] => $context["row"]) {
            // line 35
            echo "            <tr>
                ";
            // line 36
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($context["row"]);
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 37
                echo "                    <td id=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "id", array()), "html", null, true);
                echo "\" data-question=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "question", array()), "html", null, true);
                echo "\" data-answer=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "answer", array()), "html", null, true);
                echo "\" class=\"hoverable\" onclick=\"showQuestion(this)\">
                        <div class=\"card blue-grey darken-1\">
                            <div class=\"card-content white-text\">
                                <span class=\"card-title\">";
                // line 40
                echo twig_escape_filter($this->env, $context["value"], "html", null, true);
                echo "</span>
                            </div>
                        </div>

                    </td>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 46
            echo "            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['value'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 48
        echo "        </tbody>
    </table>
 ";
    }

    public function getTemplateName()
    {
        return "jeopardy.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  121 => 48,  114 => 46,  102 => 40,  91 => 37,  87 => 36,  84 => 35,  80 => 34,  74 => 30,  65 => 28,  61 => 27,  35 => 3,  32 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"base.html.twig\" %}
{% block content %}
    <!-- Modal Structure -->
    <div id=\"modal1\" class=\"modal\">
        <div class=\"modal-content\">
            <div class=\"card teal lighten-2\">
                <div class=\"card-content white-text center-align\">
                    <div class=\"card-title\" id=\"answer\"></div>
                    <div id=\"question\"></div>
                    <div id=\"countdown\"></div>
                </div>
            </div>
        </div>
        <div class=\"modal-footer\">
            <div class=\"card-action center-align\">
                <div id=\"wrong\" class=\"btn-floating red\"><i class=\"close material-icons\" onclick=\"handleAnswer(this, 'wrong')\">close</i></div>
                <div id=\"right\" class=\" btn-floating green\"><i class=\"close material-icons\" onclick=\"handleAnswer(this, 'correct')\">check</i></div>
                <div id=\"close\" class=\"waves-effect waves-green btn-flat\" onclick=\"closeAnswer(this)\"><i class=\"close material-icons\">close</i></div>
            </div>
        </div>
    </div>


    <table class=\"highlight centered\">
        <thead>
        <tr>
            {% for category in jeopardy.allCategories %}
                <th>{{ category|upper }}</th>
            {% endfor %}
        </tr>
        </thead>

        <tbody>
        {% for value, row in jeopardy %}
            <tr>
                {% for item in row %}
                    <td id=\"{{ item.id }}\" data-question=\"{{ item.question }}\" data-answer=\"{{ item.answer }}\" class=\"hoverable\" onclick=\"showQuestion(this)\">
                        <div class=\"card blue-grey darken-1\">
                            <div class=\"card-content white-text\">
                                <span class=\"card-title\">{{ value }}</span>
                            </div>
                        </div>

                    </td>
                {% endfor %}
            </tr>
        {% endfor %}
        </tbody>
    </table>
 {% endblock %}", "jeopardy.html.twig", "/var/www/jeophpardy/View/jeopardy.html.twig");
    }
}

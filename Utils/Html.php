<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 18/04/14.04.2014 14:48
 */

namespace Mindy\Utils;


class Html
{
    public static $charset = 'UTF-8';
    /**
     * @var string the HTML code to be prepended to the required label.
     * @see label
     */
    public static $beforeRequiredLabel = '';
    /**
     * @var string the HTML code to be appended to the required label.
     * @see label
     */
    public static $afterRequiredLabel = ' <span class="required">*</span>';
    /**
     * @var string the CSS class for required labels. Defaults to 'required'.
     * @see label
     */
    public static $requiredCss = 'required';
    /**
     * @var boolean whether to close single tags. Defaults to true. Can be set to false for HTML5.
     * @since 1.1.13
     */
    public static $closeSingleTags = true;
    /**
     * @var boolean whether to render special attributes value. Defaults to true. Can be set to false for HTML5.
     * @since 1.1.13
     */
    public static $renderSpecialAttributesValue = true;
    /**
     * @var callback the generator used in the {@link CHtml::modelName()} method.
     * @since 1.1.14
     */
    private static $_modelNameConverter;

    /**
     * Generates an HTML element.
     * @param string $tag the tag name
     * @param array $htmlOptions the element attributes. The values will be HTML-encoded using {@link encode()}.
     * If an 'encode' attribute is given and its value is false,
     * the rest of the attribute values will NOT be HTML-encoded.
     * Since version 1.1.5, attributes whose value is null will not be rendered.
     * @param mixed $content the content to be enclosed between open and close element tags. It will not be HTML-encoded.
     * If false, it means there is no body content.
     * @param boolean $closeTag whether to generate the close tag.
     * @return string the generated HTML element tag
     */
    public static function tag($tag, $htmlOptions = array(), $content = false, $closeTag = true)
    {
        $html = '<' . $tag . self::renderAttributes($htmlOptions);
        if ($content === false)
            return $closeTag && self::$closeSingleTags ? $html . ' />' : $html . '>';
        else
            return $closeTag ? $html . '>' . $content . '</' . $tag . '>' : $html . '>' . $content;
    }

    /**
     * Generates an open HTML element.
     * @param string $tag the tag name
     * @param array $htmlOptions the element attributes. The values will be HTML-encoded using {@link encode()}.
     * If an 'encode' attribute is given and its value is false,
     * the rest of the attribute values will NOT be HTML-encoded.
     * Since version 1.1.5, attributes whose value is null will not be rendered.
     * @return string the generated HTML element tag
     */
    public static function openTag($tag, $htmlOptions = array())
    {
        return '<' . $tag . self::renderAttributes($htmlOptions) . '>';
    }

    /**
     * Generates a close HTML element.
     * @param string $tag the tag name
     * @return string the generated HTML element tag
     */
    public static function closeTag($tag)
    {
        return '</' . $tag . '>';
    }

    /**
     * Renders the HTML tag attributes.
     * Since version 1.1.5, attributes whose value is null will not be rendered.
     * Special attributes, such as 'checked', 'disabled', 'readonly', will be rendered
     * properly based on their corresponding boolean value.
     * @param array $htmlOptions attributes to be rendered
     * @return string the rendering result
     */
    public static function renderAttributes($htmlOptions)
    {
        static $specialAttributes = [
            'autofocus' => 1,
            'autoplay' => 1,
            'async' => 1,
            'checked' => 1,
            'controls' => 1,
            'declare' => 1,
            'default' => 1,
            'defer' => 1,
            'disabled' => 1,
            'formnovalidate' => 1,
            'hidden' => 1,
            'ismap' => 1,
            'loop' => 1,
            'multiple' => 1,
            'muted' => 1,
            'nohref' => 1,
            'noresize' => 1,
            'novalidate' => 1,
            'open' => 1,
            'readonly' => 1,
            'required' => 1,
            'reversed' => 1,
            'scoped' => 1,
            'seamless' => 1,
            'selected' => 1,
            'typemustmatch' => 1,
        ];

        if ($htmlOptions === array())
            return '';

        $html = '';
        if (isset($htmlOptions['encode'])) {
            $raw = !$htmlOptions['encode'];
            unset($htmlOptions['encode']);
        } else
            $raw = false;

        foreach ($htmlOptions as $name => $value) {
            if (isset($specialAttributes[$name])) {
                if ($value === false && $name === 'async') {
                    $html .= ' ' . $name . '="false"';
                } elseif ($value) {
                    $html .= ' ' . $name;
                    if (self::$renderSpecialAttributesValue)
                        $html .= '="' . $name . '"';
                }
            } elseif ($value !== null)
                $html .= ' ' . $name . '="' . ($raw ? $value : self::encode($value)) . '"';
        }

        return $html;
    }

    /**
     * Generates a label tag.
     * @param string $label label text. Note, you should HTML-encode the text if needed.
     * @param string $for the ID of the HTML element that this label is associated with.
     * If this is false, the 'for' attribute for the label tag will not be rendered.
     * @param array $htmlOptions additional HTML attributes.
     * The following HTML option is recognized:
     * <ul>
     * <li>required: if this is set and is true, the label will be styled
     * with CSS class 'required' (customizable with CHtml::$requiredCss),
     * and be decorated with {@link CHtml::beforeRequiredLabel} and
     * {@link CHtml::afterRequiredLabel}.</li>
     * </ul>
     * @return string the generated label tag
     */
    public static function label($label, $for, $htmlOptions = array())
    {
        if ($for === false)
            unset($htmlOptions['for']);
        else
            $htmlOptions['for'] = $for;
        if (isset($htmlOptions['required'])) {
            if ($htmlOptions['required']) {
                if (isset($htmlOptions['class']))
                    $htmlOptions['class'] .= ' ' . self::$requiredCss;
                else
                    $htmlOptions['class'] = self::$requiredCss;
                $label = self::$beforeRequiredLabel . $label . self::$afterRequiredLabel;
            }
            unset($htmlOptions['required']);
        }
        return self::tag('label', $htmlOptions, $label);
    }

    /**
     * Encodes special characters into HTML entities.
     * The {@link CApplication::charset application charset} will be used for encoding.
     * @param string $text data to be encoded
     * @return string the encoded data
     * @see http://www.php.net/manual/en/function.htmlspecialchars.php
     */
    public static function encode($text)
    {
        return htmlspecialchars($text, ENT_QUOTES, self::$charset);
    }

    /**
     * Generates a valid HTML ID based on name.
     * @param string $name name from which to generate HTML ID
     * @return string the ID generated based on name.
     */
    public static function getIdByName($name)
    {
        return str_replace(array('[]', '][', '[', ']', ' '), array('', '_', '_', '', '_'), $name);
    }

    /**
     * Generates input field ID for a model attribute.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @return string the generated input field ID
     */
    public static function activeId($model, $attribute)
    {
        return self::getIdByName(self::activeName($model, $attribute));
    }

    /**
     * Generates input field name for a model attribute.
     * Unlike {@link resolveName}, this method does NOT modify the attribute name.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @return string the generated input field name
     */
    public static function activeName($model, $attribute)
    {
        $a = $attribute; // because the attribute name may be changed by resolveName
        return self::resolveName($model, $a);
    }

    /**
     * Generates input name for a model attribute.
     * Note, the attribute name may be modified after calling this method if the name
     * contains square brackets (mainly used in tabular input) before the real attribute name.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @return string the input name
     */
    public static function resolveName($model, &$attribute)
    {
        $modelName = self::modelName($model);

        if (($pos = strpos($attribute, '[')) !== false) {
            if ($pos !== 0) // e.g. name[a][b]
                return $modelName . '[' . substr($attribute, 0, $pos) . ']' . substr($attribute, $pos);
            if (($pos = strrpos($attribute, ']')) !== false && $pos !== strlen($attribute) - 1) // e.g. [a][b]name
            {
                $sub = substr($attribute, 0, $pos + 1);
                $attribute = substr($attribute, $pos + 1);
                return $modelName . $sub . '[' . $attribute . ']';
            }
            if (preg_match('/\](\w+\[.*)$/', $attribute, $matches)) {
                $name = $modelName . '[' . str_replace(']', '][', trim(strtr($attribute, array('][' => ']', '[' => ']')), ']')) . ']';
                $attribute = $matches[1];
                return $name;
            }
        }
        return $modelName . '[' . $attribute . ']';
    }

    /**
     * Generates HTML name for given model.
     * @see CHtml::setModelNameConverter()
     * @param CModel|string $model the data model or the model class name
     * @return string the generated HTML name value
     * @since 1.1.14
     */
    public static function modelName($model)
    {
        if (is_callable(self::$_modelNameConverter))
            return call_user_func(self::$_modelNameConverter, $model);

        $className = is_object($model) ? get_class($model) : (string)$model;
        return trim(str_replace('\\', '_', $className), '_');
    }
}

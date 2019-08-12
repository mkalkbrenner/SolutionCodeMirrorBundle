<?php

namespace Solution\CodeMirrorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CodeMirrorType extends AbstractType
{
    /**
     * @var array
     */
    protected $parameters;

    public function __construct($defaultsParameters)
    {
        $this->parameters = $defaultsParameters;
        if(!array_key_exists('theme', $this->parameters)){
            $this->parameters['theme'] = 'elegant';
        }
        if(!array_key_exists('mode', $this->parameters)){
            $this->parameters['mode'] = 'text/html';
        }
        if(!array_key_exists('ajax', $this->parameters)){
            $this->parameters['ajax'] = false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['parameters'] = array_merge($this->parameters, $options['parameters']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'parameters' => $this->parameters
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextareaType::class;
    }

    /**
     * Keep this to use same widget.
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'code_mirror';
    }
}

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
        $resolver->setDefaults([
            'parameters' => $this->parameters
        ]);
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

SolutionCodeMirrorBundle [![Build Status](https://api.travis-ci.org/f1nder/SolutionCodeMirrorBundle.png?branch=master)](https://travis-ci.org/f1nder/SolutionCodeMirrorBundle)
========================

Integration  [CodeMirror](http://codemirror.net/) editor in you symfony2 project.

###Install

Just add the following line to your projects composer.json require section, and update vendors:
``` js
"nitrado/code-mirror-bundle": "dev-master"
```

Enable bundle , add to `AppKernel.php`:
``` php
 new Solution\CodeMirrorBundle\SolutionCodeMirrorBundle(),
```
###Configuration
Add default parameters to `config.yml`:
``` yaml
framework:
    templating:
        form:
            resources:
                - 'SolutionCodeMirrorBundle:Form:code_mirror_widget.html.twig'

assetic:
    bundles:
        - # ... other bundles
        - SolutionCodeMirrorBundle

solution_code_mirror:
    parameters:
        mode: text/html
        lineNumbers: true
        lineWrapping: true
        theme: base16-dark
    mode_dirs:
        - '@SolutionCodeMirrorBundle/Resources/public/js/mode'
    themes_dirs:
        - '@SolutionCodeMirrorBundle/Resources/public/css/theme'
    addons_dirs:
            - '@SolutionCodeMirrorBundle/Resources/public/js/addon'
```


Install assets:
``` bash
$ ./app/console assets:install web --symlink
```

###Usage
``` php
 $builder->add('content', 'code_mirror', array(
    'required' => true,
    'parameters' => array(
         'lineNumbers' => 'true'
     )
 ));
```


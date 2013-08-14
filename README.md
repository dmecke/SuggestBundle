!IMPORTANT!
===========
This bundle is not yet usable as it contains adjustments for a specific project!

Installation
============

1. Add the following to your `composer.json` file:

    ```js
    // composer.json
    {
        // ...
        require: {
            // ...
            "cunningsoft/suggest-bundle": "0.1.*"
        }
    }
    ```

2. Run `composer update cunningsoft/suggest-bundle` to install the new dependencies.

3. Register the new bundle in your `AppKernel.php`:

    ```php
    <?php
    // in AppKernel::registerBundles()
    $bundles = array(
        // ...
        new Cunningsoft\SuggestBundle\CunningsoftSuggestBundle(),
        // ...
    );
    ```

4. Let your user entity implement the `Cunningsoft\SuggestBundle\Entity\AuthorInterface`:

    ```php
    // Acme\ProjectBundle\Entity\User.php
    <?php

    namespace Acme\ProjectBundle\Entity;

    use Cunningsoft\SuggestBundle\Entity\AuthorInterface;

    class User implements AuthorInterface
    {
        // ...
    ```

5. Map the interface to your user entity in your `config.yml`:

    ```yaml
    // app/config/config.yml
    // ...
    doctrine:
        orm:
            resolve_target_entities:
                Cunningsoft\SuggestBundle\Entity\AuthorInterface: Acme\ProjectBundle\Entity\User
    ```

6. Update your database schema:

    ```bash
    $ app/console doctrine:schema:update
    ```

7. Configure the suggest bundle:

    ```yaml
    // app/config/config.yml
    // ...
    cunningsoft_suggest:
        # number of votes every user has
        number_of_votes: 10
    ```

8. Import routes:

    ```yaml
    // app/config/routing.yml
    // ...
    cunningsoft_suggest_bundle:
        resource: "@CunningsoftSuggestBundle/Controller"
        type: annotation
    ```


Changelog
=========

* 0.1 (master)
First working version. Support for Symfony 2.3.*


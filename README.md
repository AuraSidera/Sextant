# Sextant
Flexible and unopinionated PHP routing library.

Sextant allows to easily define routes without forcing users to adhere any specific convention, nor implementing over-complicated patterns. You declare a list of [routes](#routes) as a pair ([condition](#conditions), [action](#actions)): action of first route whose condition is satisfied will be performed.

A [condition](#conditions) is anything *callable* which can return a boolean value. A bunch of ready-to-use condition models are available for you by default, but you will probably end up using the almighty *Simple* condition, which natively supports method and URL matching, and named parameters in URL.

An [action](#conditions) is anything *callable*. Really, nothing fancy here. Sextant has ready-to-use action models already available, such as "show this string", "shows this JSON", "include this PHP script", etc..

You can define a default action to perform when no route matches (useful for custom 404 pages).


# Installation
Sextant can be installed through [Composer](https://getcomposer.org/), although manual installation is available as well.

## Requirements
    php>= 7.0.0

## Composer
Add `aura/sextant` to your `composer.json` file, or run:

    composer require aura/sextant

## Manual
Clone or download this repository:

```bash
git clone https://github.com/AuraSidera/Sextant.git
```
or

```bash
wget https://github.com/AuraSidera/Sextant/archive/master.zip
```
File structure adheres to PSR-4: no big surprises when including files.


# Examples
## Basic usage: covers most use cases!
For most applications you will just need some mechanism to associate actions to URLs. That's great, and couldn't be simpler! You can just copy-paste this and ask no questions:

```php
// Initializes just a couple of stuffs
$router = new \AuraSidera\Sextant\Router();
$router->setConditionFactory(new \AuraSidera\Sextant\ConditionFactory\Simple())
       ->setActionFactory(new \AuraSidera\Sextant\ActionFactory\Script());

// Declares routes
$router->addRoute(['GET', '/'], 'homepage.php')
       ->addRoute(['GET', 'users/{id}'], 'user.php')
       ->addRoute(['GET', 'users/{id}/activities/{from:date}/{to:date}'], 'user_activities.php')
       ->setDefaultAction(new \AuraSidera\Sextant\ActionFactory\NotFound());

// Reads request state
$state = \AuraSidera\Sextant\State::getStateFromServer();

// Gets the job done!
$router->match()
```
What's going on here?
 * First we need to initialize the router. Obviously.
 * Then we set default abstract factories for conditions and actions. Not necessary, but makes things much easier!
 * We declare routes!
   * We set the `ConditionFactory\Simple` as default factory for conditions, so we can just write them as arrays `[method, URL pattern]`. And it will bind values to placeholders in brackets, like `{id}` too! Also, you can require values to be of a specific type, like `{from:date}`
   * We set the `ActionFactory\Script` as default factory for actions, so we can just write the name of a PHP file at it will be executed; GET/POST parameters, headers, named parameters from URL will be passed to the action, so no worries
 * We set a default action, using Sextant's built-in `ActionFactory\NotFound` factory
 * We tell the router to proceed and find a matching route
 That's all, really.


## Advanced usage
To get the most out of Sextant you need some knowledge about the system. Be sure to read (and understand) the [Concepts](#concepts) section before proceeding.

Using default factories is cool, but sometimes you just need more flexibility. That's fine: you can still set default factories, but override them with specific ones for some routers. Suppose you are fine with the behavior of the `ConditionFactory\Simple` most of the time, but you want an action to be performed for a specific URL pattern regardless of condition: you can use the basic usage example and add:

```php
$url_pattern_condition = new \AuraSidera\ConditionFactory\UrlPattern();
...
$router->addRoute($url_pattern_condition('respond-to-any-method'), 'script.php');
```
And it goes similar for actions, suppose you want to show a JSON document:

```php
$json_action = new \AuraSidera\ActionFactory\Json();
...
$router->addRoute(['GET', 'my-json'], $json_action('path-to-json.json'));
```
Guess what? You can mix conditions and actions:

```php
$url_pattern_condition = new \AuraSidera\ConditionFactory\UrlPattern();
$json_action = new \AuraSidera\ActionFactory\Json();
...
$router->addRoute($url_pattern_condition('respond-to-any-method'), $json_action('path-to-json.json'));
```
This becomes more verbose: that's the trade-off between being concise and being flexible.

Sextant provides some ready-to-use condition and action factories you can play around with. If you want to go even further, you can play around with *meta conditions*: they are special conditions which allows to build arbitrarily complex, boolean-based conditions. Take this purposely over-complicated condition:

```php
$method = new \AuraSidera\ConditionFactory\Method();
$url = new \AuraSidera\ConditionFactory\UrlPattern();
$and = new \AuraSidera\ConditionFactory\Conjunction();
$or = new \AuraSidera\ConditionFactory\Disjunction();
$not = new \AuraSidera\ConditionFactory\Negation();
...
$route->addRoute(
    $and(
        $or($not($method('GET')), $url('users/{id}')),
        $or($method('GET'), $url('users'), $url('users/{id}/edit')),
        $not($method('POST'))
    ),
    'some-action.php'
);
```
What is this mess? That's a route which requires:

```
(method != GET OR URL = users/{id}) AND (method = GET OR URL = users OR url = users/{id}/edit) AND (method != POST)
```
clearly that's not a realistic route, but you can see how any formula in [Conjunctive Normal Form](https://en.wikipedia.org/wiki/Conjunctive_normal_form) can be expressed by these mechanism. Sextant is therefore *complete* with respect to boolean algebra! Hurray!

TLDR: You can create custom complex boolean conditions on the fly without touching the code.

## Nerd usage
Why should you? I see, you are *that* type of person...

You can define your own conditions and actions. Bear in my the following mantra:

    condition: State => bool
    action: State => nothing
When implementing custom actions or conditions, match those types and you will be fine. You can use functions, anonymous functions, closure or callable objects (implementing the `__invoke` magic method). Here's an example:

```php
$router->addRoute(
    function (\AuraSidera\Sextant\State $state) {
        if ($state->getUrl() == 'X') {
            $state->addMatch('X') = 42;
            return true;
        }
        else {
            return count($state->getParametersAsDictionary()) < count($state->getHeadersAsDictionary());
        }
    },
    function (\AuraSidera\Sextant\Sate $state) {
        echo "This is the URL: " . $state->getUrl() . "<br>";
        echo "And these are the matches I got: ";
        print_r($state->getMatchesAsDictionary());
    }
);
```
Pretty neat, isn't it?


# Concepts
Main concepts in Sextant.

## State

A state is an object representing current state of the request. State always includes:

* URL
* method
* request parameters (GET, POST, ...) as associative array
* HTTP headers as associative array
* matches, an associative array filled with URL matching information (with any)

all of which can be retrieved using the appropriate getter method. A state can be automatically read from server by using the helper static factory method `getStateFromServer`:

```php
$state = \AuraSidera\Sextant\State::getStateFromServer();
$url = $state->getUrl();
$method = $state->getMethod();
$parameters = $state->getParametersAsDictionary();
$headers = $state->getHeadersAsDictionary();
$matches = $state->getMatchesAsDictionary();
```

State can be decorated with named entities, which can be accessed both with member and array access syntax. Trying to read an undefined entity will return `null`:

```php
$state = \AuraSidera\Sextant\State::getStateFromServer();
$state->some_name = 42;
echo $state->some_name;  // Displays 42

echo isset($state->fake) ? 'set' : 'unset';  // Displays 'unset'
echo $state->fake;  // Displays '' (a null value is print)

$state['other_name'] = 21;  // Array access works too, both for writing...
echo $state['other_name'];  // ... and reading values
echo $state->other_name;    // It is fine to mix both styles
```



## Conditions

A condition is any *callable* which returns a boolean. Sextant will automatically pass a State testing a route. Sextant also offers a number of ready-to-use condition in the form of abstract factories, under the `ConditionFactory` namespace. Factories can be instantiated and used in routes as in the following example:
```php
// Instantiates a condition factory which produces conditions matching methods
$method_factory = new \AuraSidera\ConditionFactory\Method();

// Matches only HTTP/GET requests
$method_condition_get = $method_factory('GET');

// Matches only HTTP/POST requests
$method_condition_post = $method_factory('POST');

// Conditions can be used in routes
$router->addRoute($method_condition_get, 'action_1');
$router->addRoute($method_condition_post, 'action_2');
```
It is usually more convenient to use the following, equivalent, more concise and clearer syntax:

```php
$method = new \AuraSidera\ConditionFactory\Method();

$router->addRoute($method('GET'), 'action_1');
$router->addRoute($method('POST'), 'action_2');
```

Note that, although Sextant uses classes to implement factories which produce conditions, this pattern is not mandatory. It is possible to use any type of function (including anonymous functions, closures and callable objects):

```php
function my_condition(\AuraSidera\Sextant\State $state): bool {
    return true;
}

$router->addRoute('my_condition', 'action');
```


## Actions
Actions are *callable*, without other particular requirements. They usually produce some type of output in the page, set some headers, manipulate a database, or a mix of those. Sextant will automatically inject the state when calling an action. Sextant has a number of ready-to-use actions, such as a default 404 page setting the appropriate header, a JSON renderer, a file rendered and a script executor. The `ActionFactory` contains such action factories. An action factory is an object building an action, which can be later used in a route:
```php
// Istantiates an action factory rendering a JSON document
$json_action_factory = new \AuraSidera\ActionFactory\Json();

// Renders a JSON when called
$json_action = $json_action_factory('path-to-document.json');

// Actions can be used in routes
$router->addRoute('condition', $json_action);
```
The following equivalent and more concise syntax is preferred:

```php
$json = new \AuraSidera\ActionFactory\Json();

$router->addRoute('condition', $json('path-to-document.json'));
```

Sextant used classed to implement factories which produce actions, but this is not mandatory. Custom functions can be used as well:

```php
function my_action(\AuraSidera\Sextant\State $state) {
    echo "Hello, world!";
    $state->track = 1;
}
```

Actions are allowed to change named entities associated to `$state`, but cannot modify URL, methods, parameters or headers.

## Routes

A route is an association between a condition and an action. Routes are declared inside a router, which will eventually try to match them in a given context (URL, method, headers, etc.). First route whose condition is satisfied in the context will be chosen, and its action performed. Routes are tested in the same order they are declared.

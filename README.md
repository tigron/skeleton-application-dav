# skeleton-application-dav
This skeleton applications creates a webdav application in your skeleton
project. 

## Installation

Installation via composer:

    composer require tigron/skeleton-application-dav

## Setup the application

Your webdav application should follow the following directory structure:

    - App-directory from skeleton-core
      - Your app name
        - config
        - event
        - fs

It is important to understand that every class that is created should be in
their correct namespace. The following namespaces should be used:

    event: \App\{APP_NAME}\Event
    fs: \App\{APP_NAME}\Fs

## Sabre Dav

This application uses Sabre Dav to create a webdav server. Your filesystem 
should contain classes of classname \Sabre\DAV\Collection or \Sabre\DAV\FS\File

## Events

Events can be created to perform a task at specific key points during the
application's execution.

The class should extend from `Skeleton\Core\Event` and the classname should be
within the namespace `\App\APP_NAME\Event\Context`, where
`APP_NAME` is the name of your application, and `Context` is one of the
available contexts:

- Application
- Dav

The different contexts and their events are described below.

### Application context

#### bootstrap

The bootstrap method is called before loading the application module.

    public function bootstrap() { }

#### teardown

The teardown method is called after the application's run is over.

    public function teardown() { }


### Dav context

#### get_root() {

The get_root should return the root of your filesystem.

    public function get_root() { }

#### authenticate

The authenticate method is called to authenticate a user.

    public function authenticate($username, $password) {

## FS - File System

### File - custom properties

The class that represents a file in the virtual FS (extending \Sabre\DAV\File) can implement

    public function get_properties() {
        return [ 'prop1' => 'val1', 'prop2' => 'val2' ];
    }

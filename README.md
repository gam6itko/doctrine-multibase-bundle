# DoctrineMultibaseBundle
Allows to dynamically switch base doctrine connection to multiple instances.

Let's imagine that you have your own CRM. Which has many accounts. 
You want the data for each account to be stored in its own database.
In this case, you need to have one connection that you could dynamically switch for the desired account.
This library may come in handy here.

## Installation

```php
composer require gam6itko/doctrine-multibase-bundle
```

## Configure

First you need to configure switchable doctrine connection.
```yaml
## doctrine.yaml

doctrine:
    dbal:
        default_connection: default
        connections:
            default:
               # your system database connection which contains accounts
            account: # switchable connection
                driver:   "%database_driver%"
                host:     "%database_host%"
                port:     "%database_port%"
                dbname:   "%database_name_account_prefix%"
                user:     "%database_user%"
                password: "%database_password%"

    orm:        
        entity_managers:
            default:
                # something here 
            account:
                connection: account # dynamic connection
                mappings:
                    AppAccountDataBundle: ~
```

Register connection switcher
```yaml
# services.yaml

Gam6itko\MultibaseBundle\Doctrine\ConnectionSwitcher:
    public: true
    arguments: ['@doctrine.dbal.account_connection', '%database_name_account_prefix%']
    calls:
        - [setEventDispatcher, ['@event_dispatcher']]
```

Create database
```bash
php bin/console multibase:database:create account database_account 1917
php bin/console multibase:schema:create account database_account 1917
```

Finally switch dummy account connection to real when you need. For example do it on user login.
```yaml
# services.yaml

App\EventListener\AccountConnectionListener:
    calls:
        - [setConnectionSwitcher, ['@Gam6itko\MultibaseBundle\Doctrine\ConnectionSwitcher']]
    tags:
        - {name: kernel.event_listener, event: kernel.request}
```

```php
# App\EventListener\AccountConnectionListener 

class AccountConnectionListener
{
    use ConnectionSwitcherAwareTrait;

    public function onKernelRequest(GetResponseEvent $event)
    {
        $user = $this->getUser();
        $this->connectionSwitcher->switchTo($user->getAccount()->getId());
    }
}
```

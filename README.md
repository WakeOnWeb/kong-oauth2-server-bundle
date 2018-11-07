# WakeOnWebKongOAuth2ServerBundle

**Note:** The bundle handle the technical communication with Kong and provide an authorization page. The other security 
stuffs should be managed by the application (form login, reset password, etc.).

## Configuration

_/.env_

    KONG_ADMIN_URL=http://localhost:8001
    KONG_API_URL=https://localhost:8443
    KONG_PROVISION_KEY=34cbcb435c02b87c9cd9d68ac6c86e2c

_/config/packages/wakeonweb_kong_oauth2_server.yaml_

    wakeonweb_kong_oauth2_server:
        kong:
            admin_url: '%env(KONG_ADMIN_URL)%'
            api_url: '%env(KONG_API_URL)%'
            provision_key: '%env(KONG_PROVISION_KEY)%'
        cancel_path: /logout

_/config/routes/wakeonweb_kong_oauth2_server.yaml_

    wakeonweb_kong_oauth2_server.authenticate:
        path: /authenticate
        controller: WakeOnWeb\Bundle\KongOAuth2ServerBundle\Controller\AuthenticateController
    
    wakeonweb_kong_oauth2_server.login:
        path: /api/login
        controller: WakeOnWeb\Bundle\KongOAuth2ServerBundle\Controller\LoginController

_/config/packages/security.yaml_

    security:
        # ...
        
        firewalls:
            anonymous: true
            api:
                pattern: ^/api/login$
                anonymous: ~
                json_login:
                    check_path: /api/login
            main:
                form_login:
                    login_path: /login
                    check_path: /login
                    
        access_control:
            - { path: /api/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: /login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: /, roles: IS_AUTHENTICATED_FULLY }

## OAuth2

The OAuth2 specification describe 4 grant types:

- Authorization Code Grant
- Implicit Grant
- Resource Owner Password Credentials Grant
- Client Credentials Grant

3 of them are supported out of the box. The "Client Credentials Grant" is way to specific at the moment and will be 
implemented later.

### Authorization Code Grant

The "Authorization Code Grant" workflow is handled by the 
`WakeOnWeb\Bundle\KongOAuth2ServerBundle\Controller\AuthenticateController`. Just be sure to add a routing to this controller
in order to handle it. 

_/config/routes/wakeonweb_kong_oauth2_server.yaml_

    wakeonweb_kong_oauth2_server.authenticate:
        path: /authenticate
        controller: WakeOnWeb\Bundle\KongOAuth2ServerBundle\Controller\AuthenticateController

The client application will have to route the end user to the following url: 
`http://sso.com/authorize?client_id=<id>&response_type=code`. Kong & the bundle will do the rest.

### Implicit Grant

The "Implicit Grant" workflow is handled by the `WakeOnWeb\Bundle\KongOAuth2ServerBundle\Controller\AuthenticateController`. 
Just be sure to add a routing to this controller in order to handle it.

_/config/routes/wakeonweb_kong_oauth2_server.yaml_

    wakeonweb_kong_oauth2_server.authenticate:
        path: /authenticate
        controller: WakeOnWeb\Bundle\KongOAuth2ServerBundle\Controller\AuthenticateController

The client application will have to route the end user to the following url: 
`http://sso.com/authorize?client_id=<id>&response_type=token`. Kong & the bundle will do the rest.

### Resource Owner Password Credentials Grant

The "Resource Owner Password Credentials Grant" workflow is handled by the 
`WakeOnWeb\Bundle\KongOAuth2ServerBundle\Controller\LoginController`. Just be sure to add a routing to this controller in 
order and a security firewall to handle it.

_/config/routes/wakeonweb_kong_oauth2_server.yaml_

    wakeonweb_kong_oauth2_server.login:
        path: /api/login
        controller: WakeOnWeb\Bundle\KongOAuth2ServerBundle\Controller\LoginController

_/config/packages/security.yaml_

    security:
        # ...
        
        firewalls:
            anonymous: true
            api:
                pattern: ^/api/login$
                anonymous: ~
                json_login:
                    check_path: /api/login
                    
        access_control:
            - { path: /api/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: /, roles: IS_AUTHENTICATED_FULLY }
            
### Client Credentials Grant

_not supported yet_

## IdentityResolver

The user identity can be resolved from the `UserInterface` using an `IdentityResolver`. A "username as identity" 
strategy is used per default. This means that the `UserInterface::getUsername()` result will be used as the user id. You
can change this behavior by implementing your own `IdentityResolver`.

_src/User/IdentityAsIs.php_

    <?php
    
    namespace App\User;
    
    use WakeOnWeb\Bundle\KongOAuth2ServerBundle\User\IdentityResolver;
    use Symfony\Component\Security\Core\User\UserInterface;
    
    class IdentityAsIs implements IdentityResolver
    {
        public function resolveIdentity(UserInterface $user): string
        {
            return $user->getId();
        }
    }

_/config/packages/wakeonweb_kong_oauth2_server.yaml_

    wakeonweb_kong_oauth2_server:
        # ...
        identity_resolver: App\User\IdentityAsIs

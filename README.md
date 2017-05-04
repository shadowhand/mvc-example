# MVC Examples

Examples for the talk [MVC to ADR](https://github.com/shadowhand/mvc-to-adr-talk):

- Github login via [oauth2-github](https://github.com/thephpleague/oauth2-github)
- Show a list of your repos
- Show a list of your followers
- **todo** Show a list of your stars

## Using

Install all dependencies:

```php
composer install
```

[Register a new application](https://github.com/settings/applications/new) with
the following settings:

- Homepage URL: `http://localhost:8000/`
- Authorization callback URL: `http://localhost:8000/login/complete`

Then copy the Client ID and Secret into a `.env` file:

```
GITHUB_CLIENT_ID=abc
GITHUB_CLIENT_SECRET=xyz
```

Start a local development server:

```php
php -S localhost:8000 -t public public/index.php
```

Open <http://localhost:8000/> and login.

## License

MIT License.

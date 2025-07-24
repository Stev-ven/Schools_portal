<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
final class Factory
{

    public function General($params = array())
    {
        return new General($params);
    }

    public function Db($params = array())
    {
        return new Db($params);
    }

    public function User($params = array())
    {
        return new User($params);
    }

    public function Mailer($params = array())
    {
        return new Mailer($params);
    }

    public function Views($params = array())
    {
        return new Views($params);
    }

    public function Project($params = array())
    {
        return new Project($params);
    }

    public function Payments($params = array())
    {
        return new Payments($params);
    }

    public function Reports($params = array())
    {
        return new Reports($params);
    }

    public function Newsletter($params = array())
    {
        return new Newsletter($params);
    }

    public function Blog($params = array())
    {
        return new Blog($params);
    }

    public function More($params = array())
    {
        return new More($params);
    }

    public function Render($params = array())
    {
        return new Render($params);
    }

    public function Settings($params = array())
    {
        return new Settings($params);
    }

    public function Admin($params = array())
    {
        return new Admin($params);
    }

    public function Request($params = array())
    {
        return new Request($params);
    }

}

# symfony-repositemap-bundle
RepoSiteMapbundle is a symfony 2 bundle which provides creating sitemap.xml from **static pages** and from **dynamic pages** (like posts, articles, products details page) so the bundle will be generate **urls for each entities view page**.

##Requirements (minimum)
  * PHP 5.3.0
  * Symfony 2.3.0
  * [@Route annotation](http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/routing.html)
   
  
You can find the requirements in the composer.json too.

##Installation
Add to the `composer.json` in the root folder.
```
"require": {
        //...
        "botalaszlo/symfony-repositemap-bundle": "dev-master"
    }
```

Add the bundle to the `RegisterBundle` function in the `app/AppKernel.php`
```
public function registerBundles()
    {
        $bundles = array(
            //...
            new RepoSiteMapBundle\RepoSiteMapBundle(),
        );
    }
```

Add the bundle to the `routing.yml` in the `app/config.yml`
```
# RepoSiteMap Bundle
RepoSiteMapBundle:
    resource: @RepoSiteMapBundle/Controller/
    type:     annotation
```


##Usage
For the usage you have to use **@Route annotations**.
###Static pages
You have to add the `"sitemap"=true` value in `options` of the @Route annotation.
This will generate one url for the Home controller's index action.
```
    /**
     *
     * @Route("/home/index ", name="AppBundle_home_index", options={"sitemap"=true})
     */
    public function indexAction() {
      //...
    }
```
###Dynamic pages
If you have view pages for entities, like for "posts", "articles", "products" then you have to add the **entity's path** in the options. So the bundle will be **dynamicly generate urls for each entities detail view**.
Use this format: `"sitemap" = {"repository" = "[[EntityPath]]"`
```
    /**
     * @Route("/product/{id} ", name="AppBundle_product", options={"sitemap" = {"repository" = "AppBundle:Slip"}})
     */
    public function listAction() {
      //...
    }
```
In this case the trick that you add the entity's path. Then bundle will count the entities in the table to get to know how many rows are in the table. So it will be dynamicly generate the view page for each entities.

##Todo
This bundle has very limited features. It does not handle the date, frequency or priority values for sitemap urls. This features will be implemented in the future.

If you have any advice, do not hesitate inform me.

# EasyAdmin

## Personnaliser la page d'accueil dans le dashboard

Dans le dossier `templates`, on crée un dossier `bundles/EasyAdminBundle/` .
Puis dans `bundles/EasyAdminBundle/` on va mettre tout le contenu qu'il y a dans le dossier `views` de EasyAdmin qui est dans le `vendor` à cette adresse  `\vendor\easycorp\easyadmin-bundle\src\Resources\views` .

Dans la class `DashboardController`, la méthode `index()` retourne l'autre `méthode index()` qui provient du controller parent qui est `AbstractDashboardController` 
ça retourne simplement le fichier twig : `welcome.html.twig`. 
Et comme, on a ce même fichier twig : `welcome.html.twig` dans notre nouveau dossier `bundles/EasyAdminBundle/`, c'est notre fichier qui va être pris en compte maitenant.

pour mieux personnalité notre fichier `welcome.html.twig`, on va enlever le return parent::index(); du parent. pour mettre notre propre chemin du render `return $this->render("bundles/EasyAdminBundle/welcome.html.twig", [] );`

Dans la classe `DashboardController`

Avant

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }


Après

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render("bundles/EasyAdminBundle/welcome.html.twig",

        );
    }

Voilà maintenant, on peut personnaliser notre fichier `welcome.html.twig`. 
EasyAdmin utilise Bootstrap de base donc on peut utiler les classes de Bootstrap si on veut. 
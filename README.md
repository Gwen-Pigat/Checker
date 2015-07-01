Petit fichier qui me permet de générer aléatoirement mes mots de passe selon la méthode "str_shuffle".<br>
Le refresh actualise le MDP mais ne l'insère pas en BDD. Pour cela il faudra cliquer sur "Générer un nouveau"<br>
La deuxième fonctionnalité du Checker est sa possiblité de gérer ses dépenses.<br>
La fonction "ajout" et "dépenses" communiquent directement avec la BDD afin de rétirer ou ajouter la somme donnée par rapport à la valeur par défault<br>

De plus afin de conserver l'aspect pratique de la chose j'ai décidé d'utiliser les methodes $_GET. Cela permet donc d'avoir tout en un seul fichier et étant donné le contenu de celui-ci la visibilité du code reste satisfaisante.

from django.db import models
from django.utils.text import slugify


class MembreEquipe(models.Model):
    nom = models.CharField(max_length=100)
    poste = models.CharField(max_length=100)
    photo = models.ImageField(upload_to="membres/", blank=True, null=True)

    def __str__(self):
        return self.nom
    
class Temoignage(models.Model):
    nom = models.CharField(max_length=100)
    fonction = models.CharField(max_length=100)
    message = models.TextField()
    image = models.ImageField(upload_to="temoignages/", blank=True, null=True)

    def __str__(self):
        return self.nom
    
class ServiceRealises(models.Model):
    description = models.CharField()
    
    def __str__(self):
        return self.description
    
class Footer(models.Model):
    texte = models.CharField()
     
    def __str__(self):
        return "configuration fotter"
    
class FooterLink(models.Model):
    footer = models.ForeignKey(Footer,related_name="links",on_delete=models.CASCADE)
    nom = models.CharField(max_length=50,help_text="nom du reseau")
    url = models.URLField(help_text="lien vers le site")
    icone = models.CharField(max_length=50,help_text="classe css FontAwesome, ex: fab fa-facebook")
    
    def __str__(self):
        return f"{self.nom}-{self.url}"
    
class Service(models.Model):
    titre = models.CharField(max_length=100)
    description = models.TextField()
    icone = models.CharField(max_length=100, help_text="Classe de l'icone  (ex:'fa-solid fa code')")
    couleur =models.CharField(max_length=50, default="text-blue-500", help_text="Classe Tailwind (ex: text-red-500)")
    slug = models.SlugField(unique=True, blank=True,null=True)
    
    def save(self,*args,**kwargs):
        if not self.slug:
            self.slug = slugify(self.titre)
        super().save(*args, **kwargs)
        
    def __str__(self):
        return self.titre
    
class DemandeService(models.Model):
    service = models.ForeignKey(Service, on_delete=models.CASCADE, related_name='demandes')
    nom = models.CharField(max_length=120)
    email = models.EmailField()
    telephone = models.CharField(max_length=30 , blank=True)
    objectif = models.CharField(max_length=150 , blank=True ,help_text="but principal du projet")
    message = models.CharField()
    budget_estime = models.CharField(max_length=50, blank=True)
    delai_souhaite = models.DateField(blank=True , null=True)
    fichier = models.FileField(upload_to='briefs/', blank=True,null=True)
    created_at = models.DateTimeField(auto_now_add=True)
   
    class Meta:
        ordering = ('-created_at',)
        
    def __str__(self):
        return f"Demande {self.service.titre}-{self.nom}"
from django.contrib import admin

admin.site.site_header = "Administration VORTECH"
admin.site.site_title = "Vortech Admin"
admin.site.index_title = "Bienvenue sur l'espace d'administration Vortech"

# Register your models here.d
from django.contrib import admin
from .models import MembreEquipe
from .models import Temoignage
from .models import ServiceRealises
from .models import Footer,FooterLink
from .models import Service , DemandeService

class MembreEquipeAdmin(admin.ModelAdmin):
    list_display = ("nom", "poste")

admin.site.register(MembreEquipe, MembreEquipeAdmin) 

class TemoignageAdmin(admin.ModelAdmin):
    list_display = ("nom","fonction","message")
    
admin.site.register(Temoignage, TemoignageAdmin) 

class ServiceRealisesAdmin(admin.ModelAdmin):
    list_display = ("description",)
    
admin.site.register(ServiceRealises, ServiceRealisesAdmin) 

class FooterAdmin(admin.ModelAdmin):
    list_display = ("footer",)
    

class FooterLinkInline(admin.TabularInline):
    model = FooterLink
    extra = 1
    
@admin.register(Footer)
class FooterAdmin(admin.ModelAdmin):
    inlines = [FooterLinkInline]


@admin.register(Service)
class ServiceAdmin(admin.ModelAdmin):
    list_display = ('titre','icone','couleur', 'slug')
    prepopulated_fields = {'slug': ('titre',)}
    search_fields = ('titre',)
    
    
@admin.register(DemandeService)
class DemandeServiceAdmin(admin.ModelAdmin):
    list_display = ('service', 'nom' , 'email' , 'telephone' , 'created_at')
    search_fields = ('nom' , 'email' , 'service__titre')
    list_filter = ('service' , 'created_at')
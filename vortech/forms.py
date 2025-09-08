from django import forms
from .models import DemandeService

COMMON_INPUT = 'block w-full rounded-lg border border-gray-600 bg-gray-800 text-gray-200 p-3 focus:outline-none focus:ring-2 focus:ring-blue-500'
class DemandeServiceForm(forms.ModelForm):
    class Meta:
        model = DemandeService
        fields = ['nom',"email", "telephone", "objectif", "message", "budget_estime", "delai_souhaite", "fichier"]
        widgets = {
            'nom': forms.TextInput(attrs={'class': COMMON_INPUT, 'placeholder': 'Votre nom complet'}),
            'email': forms.EmailInput(attrs={'class': COMMON_INPUT, 'placeholder': 'ex: vous@mail.com'}),
            'telephone': forms.TextInput(attrs={'class': COMMON_INPUT, 'placeholder': '+243...'}),
            'objectif': forms.TextInput(attrs={'class': COMMON_INPUT, 'placeholder': 'Ex: site vitrine pour ma boutique'}),
            'message': forms.Textarea(attrs={'class': COMMON_INPUT, 'rows': 5, 'placeholder': 'Décrivez votre besoin'}),
            'budget_estime': forms.TextInput(attrs={'class': COMMON_INPUT, 'placeholder': 'Ex: 100-500$'}),
            'delai_souhaite': forms.DateInput(attrs={'type': 'date', 'class': COMMON_INPUT}),
            'fichier': forms.ClearableFileInput(attrs={'class': 'block w-full text-gray-200'}),
        }

class ContactForm(forms.Form):
    nom = forms.CharField(max_length=100,required=True)
    numero = forms.CharField(max_length=20,required=True)
    message = forms.CharField(widget=forms.Textarea,required=True)
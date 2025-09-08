
from django.shortcuts import render
from .models import MembreEquipe
from .models import Temoignage
from .models import ServiceRealises
from .models import Footer
from .models import Service
from django.utils.text import slugify
from .forms import DemandeServiceForm
from django.shortcuts import redirect, render
from django.shortcuts import render
from .forms import ContactForm 
from django.shortcuts import render, get_object_or_404, redirect
from django.contrib import messages
from django.core.mail import send_mail
from django.conf import settings

def accueil(request):
    footer = Footer.objects.first()
    return render(request, 'index.html',{"footer": footer})
def apropos(request):
    membres = MembreEquipe.objects.all()
    temoignages = Temoignage.objects.all()
    servicerealises = ServiceRealises.objects.all()
    footer = Footer.objects.first()
    return render(request, 'apropos.html', {"membres": membres ,"temoignages": temoignages , "servicerealises": servicerealises , "footer": footer})
def contact(request):
    
    return render(request, 'contact.html',)
def service(request):
    footer = Footer.objects.first()
    services = Service.objects.all()
    return render(request, 'service.html',{"footer": footer, "services":services})

def demander_service(request, slug):
    footer = Footer.objects.first()
    service = get_object_or_404(Service, slug=slug)

    if request.method == 'POST':
        form = DemandeServiceForm(request.POST, request.FILES)
        if form.is_valid():
            demande = form.save(commit=False)
            demande.service = service
            demande.save()

            subject = f"[VORTECH] Nouvelle demande: {service.titre} - {demande.nom}"
            message = (
                f"Service: {service.titre}\n"
                f"Nom: {demande.nom}\n"
                f"Email: {demande.email}\n"
                f"Téléphone: {demande.telephone}\n"
                f"Objectif: {demande.objectif}\n"
                f"Budget: {demande.budget_estime}\n"
                f"Délai: {demande.delai_souhaite}\n\n"
                f"Message:\n{demande.message}\n"
            )
            send_mail(subject,message,settings.DEFAULT_FROM_EMAIL,['benitobapela@gmail.com'],fail_silently=False,)

            messages.success(request, "🚀 Merci ! Votre demande a bien été envoyée.")
            return redirect('merci_demande')
    else:
        form = DemandeServiceForm()

    return render(request, 'demander_service.html', {'service': service, 'form': form, 'footer': footer})

def merci_demande(request):
    return render(request, 'merci_demande.html')

for s in Service.objects.all():
    if not s.slug:
        s.slug = slugify(s.titre)
        s.save()
        
def contact(request):
    footer = Footer.objects.first()
    if request.method == 'POST':
        form = ContactForm(request.POST)
        if form.is_valid():
            nom = form.cleaned_data['nom']
            numero = form.cleaned_data['numero']
            message = form.cleaned_data['message']

            texte= f"Bonjour_vortech , je_suis {nom}. Numero:{numero}. Message:{message}"
            whatsapp_url ="https://wa.me/243991276154?text="+ texte.replace("", "%20")
            
            return redirect(whatsapp_url)
    else:
        form = ContactForm()

    return render(request, 'contact.html', {'form': form,'footer': footer})
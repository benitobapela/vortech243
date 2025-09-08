from django.contrib import admin
from django.urls import path
from .views import *
from django.conf import settings
from django.conf.urls.static import static

urlpatterns = [
    path('vortex&technologie_admin/', admin.site.urls),
    path('', accueil,name='accueil'),
    path('apropos/', apropos,name='apropos'),
    path('contact/', contact,name='contact'),
    path('service/', service,name='service'),
    path('services/', service,name='services'),
    path('demande/<slug:slug>/', demander_service, name='demander_service'),
    path('merci/demande/', merci_demande, name='merci_demande'),
]

if settings.DEBUG:
    urlpatterns += static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
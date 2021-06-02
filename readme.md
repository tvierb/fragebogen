# Umfragetool

Dies ist ein Stück Software, mit dem man eine eher einfache Online-Umfrage machen könnte.

Die Fragen werden in einer YAML-Datei definiert, diese sollte eine UUID als Dateinamen haben:

    touch $(uuid).yml

Jede Frage muss eine eindeutige ID haben, die keine HTML-Zeichen enthalten darf. Denn diese IDs werden im generierten HTML verwendet.

Zum Aufrufen dieser Umfrage requestet man

    httpx://irgendeine/domain/index.php?uid=...<hier die uuid einsetzen>

Die Antworten des Surfers werden als JSON-Strukur im Webserver-Log gespeichert.
Beispiel:

    {"surveyresult":{"p1-1":"n","p1-2":"j","p1-3":"7","p1-4":"5","p1-5":["vim","forth"]}}

Es obliegt dem Auswerter der Antworten, diese auf gültige Eingaben/Auswahlen des Surfers zu prüfen.
Denn jeder Surfer kann die Formulare in seinem Browser verändern.

Wenn man diese Software ernsthaft einsetzen möchte, sollte man ggf. noch irgendwie dafür sorgen, dass niemand zu viele POSTs pro Zeitintervall senden kann.

Ansonsten werden Webserverlogs mit der Zeit komprimiert und nach ein paar Tagen auch automatisch gelöscht.
Das erspart uns einerseits das Aufräumen alter Antworten, andererseits müssen wir uns dann auch aktiv darum kümmern, sie auszulesen.
Sonst sind sie halt weg.

## Hosting

Die Software kann überall dort eingesetzt werden, wo es PHP 7 und php-yaml gibt.
Man kann es auch im LAN einsetzen mittels des built-in-Webservers von PHP.

Dazu einfach im Ordner "fragebogen" dies starten:

    php -S 127.0.0.1:8080 -t . 2>&1 | tee -a survey.log 

## Disclaimer

Die Verwendung diese Software geschieht natürlich auf eigene Gefahr.


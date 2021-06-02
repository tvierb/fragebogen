# Umfragetool

Dies ist ein Stück Software, mit dem man eine eher einfache Online-Umfrage machen könnte.

Die Fragen werden in einer YAML-Datei definiert, diese sollte eine UUID als Dateinamen haben:

    touch $(uuid).yml

Zum Aufrufen dieser Umfrage requestet man

    httpx://irgendeine/domain/index.php?uid=...<hier die uuid einsetzen>

Die Antworten des Surfers werden als JSON-Strukur im Webserver-Log gespeichert.
Beispiel:

    {"surveyresult":{"p1-1":"n","p1-2":"j","p1-3":"7","p1-4":"5","p1-5":["vim","forth"]}}

Es obliegt dem Auswerter der Antworten, diese auf gültige Eingaben/Auswahlen des Surfers zu prüfen.
Denn jeder Surfer kann die Formulare in seinem Browser verändern.

Wenn man diese Software ernsthaft einsetzen möchte, sollte man ggf. noch eine Reload-Bremse einbauen. Also irgendwie dafür sorgen, dass man durch oftmaliges Reloaden des POST-Requests "seiner" Ansicht der Dinge mehr Gewicht geben kann.

Die Verwendung geschieht auf eigene Gefahr.


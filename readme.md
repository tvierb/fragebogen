# Umfragetool

Dies ist ein Stueck Software, mit dem man eine eher einfache Online-Umfrage machen koennte.

Die Fragen werden in einer YAML-Datei definiert, diese sollte eine UUID als Dateinamen haben:

    touch $(uuid).yml

Zum Aufrufen dieser Umfrage requestet man

    httpx://irgendeine/domain/index.php?uid=...<hier die uuid einsetzen>

Offen bzw zu implementieren ist noch, was mit den Formulardaten gemacht wird. Man koennte sie bswp. ein eine Logdatei schreiben. Vielleicht gar die vom Webserver.
Hm, die Idee gefaellt mir :-)

Man sollte aber dann auch eine Reload-Bremse (per IP?) einbauen. Ggf.

Ip-Symcon Modul zur Einbindung einer
Heizungsanlage mit Buderus Logamatic 4000 Steuerung über das Buderus RS232 Gateway.

Folgende Buderus Logamatic Steuerung kann eingebunden werden:
- Logamatic 43xx
- FM 441 (1 Heizkreis, Warmwasser)
- FM 442 (2 Heizkreise)
- FM 443 (Solar)
- FM 444 (Alternativer Wärmeerzeuger)
- ZM 432 (bodenstehender Kessel)

Erforderliche Installation:

- Symcon Version >= 4.1
- Protokoll Konverter (z.B. LeaOil ProtocolControlModule (Trial) Version 1.50, http://www.leaoil.de/files/pcm150t.exe)

Installation über Symcon Modules.
https://github.com/hermanthegerman2/Logamatic4000

Es muß nur das Modul Logamatic 43xx als neue Instanz installiert werden und danach in der Instanz das Menü "Monitordaten auslesen" angewählt werden. Danach sollten alle tatsächlich vorhandenen Funktionsmodule (FM 441, FM 442, FM 443 und FM 444) gefunden und installiert werden. Der Protocol konverter ist erforderlich, um das Protokoll 3964R zu verarbeiten und kommuniziert sowohl zum Buderus Gateway über RS232 und zu Symcon über TCP Port 6200.



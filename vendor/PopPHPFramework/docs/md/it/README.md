Pop PHP Framework
=================

Documentation : Overview
------------------------

Il quadro Pop PHP è un framework orientato agli oggetti PHP con un facile da usare API che vi permetterà di utilizzare una vasta gamma di funzionalità. Potete usarlo come strumento per assistere rapidamente la scrittura di script di base, oppure è possibile utilizzarlo come un vero e proprio quadro per costruire e personalizzare su larga scala, applicazioni robuste. Al centro del quadro è un gruppo di componenti, di cui, alcuni possono essere utilizzati in modo indipendente e alcuni possono essere utilizzati insieme per sfruttare la potenza completa del quadro e PHP.


* Archive
* Auth
* Cache
* Cli
* Code
* Color
* Compress
* Config
* Curl
* Data
* Db
* Dir
* Dom
* Feed
* File
* Filter
* Font
* Form
* Ftp
* Geo
* Graph
* Http
* Image
* Loader
* Locale
* Mail
* Mvc
* Paginator
* Payment
* Pdf
* Project
* Record
* Validator
* Version
* Web

QuickStart

----------

Ci sono due modi che si possono ottenere installato e funzionante con il quadro di Pop PHP.


Se stai solo cercando di scrivere alcuni script veloci, si può semplicemente eliminare la cartella di origine nella cartella del progetto di lavoro, fare riferimento alla 'bootstrap.php' di conseguenza in uno script e iniziare a scrivere codice. Troverete tutti i riferimenti ed esempi in tutta questa documentazione che spiegherà i vari componenti e come si possono usare.


If you're looking to build a larger-scale application, you can use the CLI component to create the project's base foundation, or scaffolding. This way, you can start writing project code quickly and not have to burdened with getting everything up and running. All you have to do is define your project in single installation file, run the Pop CLI command using that file and - voila! - Pop does all the dirty work for you and you can get to writing project code faster. Review the documentation on the CLI component to further explore how to take advantage of this robust component.

Il MVC Component

-----------------

Il componente MVC è disponibile e particolarmente utile quando si costruisce una applicazione su vasta scala. MVC sta per Model-View-Controller ed è un modello di progettazione che facilita una ben organizzata separazione degli interessi. Esso consente la presentazione, business logic e accesso ai dati a tutti essere conservati separatamente.


The controller receives input (i.e. a web request) from the user and based on that input, communicates that with the model. The model can then process the request to determine what data or response is needed. At that point, the model and view communicate so that the view can build the presentation, or view, based on the data obtained from the model. Then, the controller will communicate with the view to display the appropriate output to the user.

One extra piece of the MVC component that is available with the Pop PHP Framework is a router. The router is simply an additional layer on top that does exactly what its name suggests  it routes different types of user requests to their corresponding controllers. In other words, it provides an easy way to manage multiple user paths and controllers.

Spesso, può essere difficile cogliere il pattern MVC di progettazione fino a quando effettivamente iniziare ad usarlo. Una volta fatto, però, vedrete immediatamente il vantaggio di avere tutto separato in facili da gestire concetti con molto poco, se del caso, si sovrappongono. Il controller gestisce la delega delle richieste, il modello gestisce la logica di business e la vista determina come per visualizzare l'output per l'utente. Di gran lunga, questo modello trionfi dei giorni antichi di stipare tutto in un singolo script o script vari che sono inclusi in tutto il luogo creando un gran casino. Basta provare e vedrete!


I DB & Record Components

--------------------------

I componenti DB e Record sono due componenti che hanno il potenziale per essere utilizzato un po 'in qualsiasi applicazione. Ovviamente, il componente Db offre un accesso diretto per interrogare un database. Le schede supportate sono native MySQL, MySQLi, PgSQL, SQLite e DOP. Essi servono a normalizzare l'accesso al database in ambienti diversi in modo che non ci si deve preoccupare tanto di re-tooling un'applicazione per lavorare con un diverso tipo di database in un ambiente diverso.


Il componente Record è un componente potente che fornisce un accesso standardizzato ai dati all'interno di un database, in particolare le tabelle del database e dei singoli record all'interno delle tabelle. Il componente record è in realtà un ibrido tra il record attivo e dati della tabella Gateway modelli. Essa può fornire l'accesso a una singola riga o disco come un modello Active Record sarebbe, o più righe in una sola volta, come un Gateway Tabella dati avrebbe fatto. Con il Framework PHP Pop, l'approccio più comune è quello di scrivere una classe figlia che estende la classe Record che rappresenta una tabella nel database. Il nome della classe figlio dovrebbe essere il nome della tabella. Con la semplice creazione di


<pre>
use Pop\Record\Record;

class Users extends Record { }
</pre>

you create a class that has all of the functionality of the Record component built in and the class knows the name of the database table to query from the class name. For example,  'Users' translates into `users` or 'DbUsers' translates into `db_users` (CamelCase is automatically converted into lower_case_underscore.) Review the Record documentation to see how you can fine tune the child table class.

(c) 2009-2012 [Moc 10 Media, LLC.](http://www.moc10media.com) All Rights Reserved.
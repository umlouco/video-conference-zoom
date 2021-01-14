<div>
    <div class="container">
        <form action="#" method="post">
            <input type="hidden" name="meetingId" value="<?php echo $meetingId; ?>">
            <div class="row">
                <div class="col-sm-3">
                    <label>Email</label>
                </div>
                <div class="col-sm-9">
                    <input type="text" name="email">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label>Birth Date</label>
                </div>
                <div class="col-sm-9">
                    <input type="date" name="birth_date">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label>Gender</label>
                </div>
                <div class="col-sm-9">
                    <input type="radio" name="gender" value="M"> M
                    <input type="radio" name="gender" value="F"> F
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label>Provincia</label>
                </div>
                <div class="col-sm-9">
                    <select id="provincia" name="provincia">
                        <option value="">-</option>
                        <option value="1">Agrigento</option>
                        <option value="2">Alessandria</option>
                        <option value="88">Ancona</option>
                        <option value="3">Aosta</option>
                        <option value="6">Arezzo</option>
                        <option value="4">Ascoli Piceno</option>
                        <option value="89">Asti</option>
                        <option value="7">Avellino</option>
                        <option value="8">Bari</option>
                        <option value="109">Barletta-Andria-Trani</option>
                        <option value="10">Belluno</option>
                        <option value="11">Benevento</option>
                        <option value="9">Bergamo</option>
                        <option value="90">Biella</option>
                        <option value="12">Bologna</option>
                        <option value="14">Bolzano</option>
                        <option value="91">Brescia</option>
                        <option value="13">Brindisi</option>
                        <option value="15">Cagliari</option>
                        <option value="92">Caltanissetta</option>
                        <option value="16">Campobasso</option>
                        <option value="110">Carbonia-Iglesias</option>
                        <option value="17">Caserta</option>
                        <option value="23">Catania</option>
                        <option value="93">Catanzaro</option>
                        <option value="18">Chieti</option>
                        <option value="20">Como</option>
                        <option value="22">Cosenza</option>
                        <option value="21">Cremona</option>
                        <option value="34">Crotone</option>
                        <option value="19">Cuneo</option>
                        <option value="25">Enna</option>
                        <option value="115">Fermo</option>
                        <option value="26">Ferrara</option>
                        <option value="28">Firenze</option>
                        <option value="106">Fiume</option>
                        <option value="27">Foggia</option>
                        <option value="29">Forli' - Cesena</option>
                        <option value="94">Frosinone</option>
                        <option value="30">Genova</option>
                        <option value="31">Gorizia</option>
                        <option value="32">Grosseto</option>
                        <option value="33">Imperia</option>
                        <option value="95">Isernia</option>
                        <option value="5">L'Aquila</option>
                        <option value="68">La Spezia</option>
                        <option value="38">Latina</option>
                        <option value="36">Lecce</option>
                        <option value="35">Lecco</option>
                        <option value="37">Livorno</option>
                        <option value="96">Lodi</option>
                        <option value="39">Lucca</option>
                        <option value="40">Macerata</option>
                        <option value="42">Mantova</option>
                        <option value="44">Massa Carrara</option>
                        <option value="45">Matera</option>
                        <option value="112">Medio Campidano</option>
                        <option value="41">Messina</option>
                        <option value="97">Milano</option>
                        <option value="43">Modena</option>
                        <option value="105">Monza Brianza</option>
                        <option value="98">Napoli</option>
                        <option value="46">Novara</option>
                        <option value="47">Nuoro</option>
                        <option value="113">Ogliastra</option>
                        <option value="111">Olbia-Tempio</option>
                        <option value="48">Oristano</option>
                        <option value="50">Padova</option>
                        <option value="49">Palermo</option>
                        <option value="55">Parma</option>
                        <option value="58">Pavia</option>
                        <option value="52">Perugia</option>
                        <option value="56">Pesaro - Urbino</option>
                        <option value="51">Pescara</option>
                        <option value="99">Piacenza</option>
                        <option value="53">Pisa</option>
                        <option value="57">Pistoia</option>
                        <option value="107">Pola</option>
                        <option value="100">Pordenone</option>
                        <option value="101">Potenza</option>
                        <option value="54">Prato</option>
                        <option value="62">Ragusa</option>
                        <option value="59">Ravenna</option>
                        <option value="60">Reggio Calabria</option>
                        <option value="61">Reggio Emilia</option>
                        <option value="102">Rieti</option>
                        <option value="64">Rimini</option>
                        <option value="63">Roma</option>
                        <option value="65">Rovigo</option>
                        <option value="66">Salerno</option>
                        <option value="70">Sassari</option>
                        <option value="104">Savona</option>
                        <option value="103">Siena</option>
                        <option value="69">Siracusa</option>
                        <option value="67">Sondrio</option>
                        <option value="71">Taranto</option>
                        <option value="72">Teramo</option>
                        <option value="76">Terni</option>
                        <option value="74">Torino</option>
                        <option value="75">Trapani</option>
                        <option value="73">Trento</option>
                        <option value="78">Treviso</option>
                        <option value="77">Trieste</option>
                        <option value="79">Udine</option>
                        <option value="80">Varese</option>
                        <option value="83">Venezia</option>
                        <option value="81">Verbano-Cusio-Ossola</option>
                        <option value="82">Vercelli</option>
                        <option value="85">Verona</option>
                        <option value="87">Vibo Valentia</option>
                        <option value="84">Vicenza</option>
                        <option value="86">Viterbo</option>
                        <option value="108">Zara</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label>What is your problem</label>
                </div>
                <div class="col-sm-9">
                    <textarea name="problem"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label>Passwor</label>
                </div>
                <div class="col-sm-9">
                    <input type="password" name="password">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label>Confirm password</label>
                </div>
                <div class="col-sm-9">
                    <input type="password" name="password2">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">

                </div>
                <div class="col-sm-9">
                    <input type="submit" id="calendar-login" class="btn btn-primary" value="Register">
                </div>
            </div>
        </form>
    </div>
</div>
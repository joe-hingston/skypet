<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SkyPet</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>
<body class="font-sans">

<div id="app">
    <header class="section py-10 px-20 mb-8" style="background: url('/images/splash2.png') 0 15px no-repeat;">
        <h1>
            <img alt="Vetdata" src="/images/logo2.png">
        </h1>
    </header>

    <div class="section pb-10 max-w-920px">
        <div class="container">
            <main class="flex">
                <aside class="w-64 px-8 pt-8 w-64">
                    <div class="pt-3 mb-8">
                        <h4 class="uppercase font-bold mb-3 text-base">The Brand</h4>
                        <ul class="list-reset">
                            <li class="text-lg py-3 leading-loose">
                                <router-link class="text-black" to="/" exact>Home</router-link>
                            </li>
                            <li class="text-lg py-3 leading-loose">
                                <router-link class="text-black" to="/loaders-and-animations">About</router-link>
                            </li>
                            <li class="text-lg py-3  leading-loose">
                                <router-link class="text-black" to="/machinelearning">Machine Learning</router-link>
                            </li>
                            <li class="text-lg py-3 leading-loose">
                                <router-link class="text-black" to="/contact">Contact Us</router-link>
                            </li>
                        </ul>
                    </div>
                    </section>

                    <section>
                        <div class="uppercase font-bold mb-3 text-base">Data</div>
                        <div class="pt-3 mb-8">
                            <ul class="list-reset">
                                <li class="text-lg py-3 leading-loose">
                                    <router-link class="text-black" to="/journals">Journals</router-link>
                                </li>

                                <li class="text-lg py-3 leading-loose">
                                    <router-link class="text-black" to="/outputs">Outputs</router-link>
                                </li>
                                <li class="text-lg py-3 leading-loose">
                                    <router-link class="text-black" to="/computed">Computed</router-link>
                            </ul>
                        </div>
                    </section>

                    <section>
                        <div class="uppercase font-bold mb-3 text-base">Admin</div>
                        <div class="pt-3 mb-8">
                            <ul class="list-reset">
                                <li class="text-lg py-3 leading-loose">
                                    <router-link class="text-black" to="/journalAdmin">JournalAdmin</router-link>
                                </li>
                            </ul>
                        </div>
                    </section>

                </aside>

                <div class="primary flex-1 ">
                    <router-view></router-view>
                </div>
            </main>
        </div>
    </div>
</div>
<script src="/js/app.js"></script>

</body>
</html>

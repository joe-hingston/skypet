import Home from './components/Home';
import Contact from './components/Contact';
import Computed from './components/Computed';
import Journals from './components/Journals';
import MachineLearning from './components/MachineLearning';
import Outputs from './components/Outputs';
import WhatWeDo from './components/WhatWeDo';
import NotFound from './components/NotFound';
import JournalAdmin from './components/JournalAdmin';
import JournalView from './components/JournalView';

const LoadersAndAnimations = () =>
    import(/* webpackChunkName: "loaders-and-animations.bundle" */ './components/LoadersAndAnimations');

export default {

    mode: 'history',

    linkActiveClass: 'font-bold',

    routes: [

        {
          path:'*',
          component: NotFound,
        },
        {
            path: '/',
            component: Home,
            name: 'home'
        },
        {
            path: '/loaders-and-animations',
            component: LoadersAndAnimations
        },
        {
            path: '/contact',
            component: Contact,
            name: 'contact'
        },
        {
            path: '/computed',
            component: Computed,
            name: 'computed'
        },
        {
            path: '/journals',
            component: Journals,
            name: 'journals'
        },
        {
            path: '/machinelearning',
            component: MachineLearning,
            name: 'machinelearning'
        },
        {
            path: '/whatwedo',
            component: WhatWeDo,
            name: 'whatwedo'
        },
        {
            path: '/outputs',
            component: Outputs,
            name: 'outputs'
        },
        {
            path: '/journalAdmin',
            component: JournalAdmin,
            name: 'JournalAdmin'
        },
        {
            path: '/journalView',
            component: JournalView,
            name: 'JournalView'
        }
    ]

}

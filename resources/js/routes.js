import Home from './components/Home';
import Contact from './components/Contact';
import Computed from './components/Computed';
import Journals from './components/Journals';
import MachineLearning from './components/MachineLearning';
import Outputs from './components/Outputs';
import WhatWeDo from './components/WhatWeDo';
import NotFound from './components/NotFound';
import Test from './components/Test';
import JournalAdmin from './components/JournalAdmin';

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
            path: '/outputs',
            component: Outputs,
            name: 'outputs'
        },
        {
            path: '/whatwedo',
            component: WhatWeDo,
            name: 'whatwedo'
        },
        {
            path: '/test',
            component: Test,
            name: 'test'
        },
        {
            path: '/journalAdmin',
            component: JournalAdmin,
            name: 'JournalAdmin'
        }
    ]

}

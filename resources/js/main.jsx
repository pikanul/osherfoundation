import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import './index.css'
import App from './App.jsx'
import { createBrowserRouter } from "react-router";
import { RouterProvider } from "react-router";
import Root from './component/layout/Root.jsx';
import Home from './component/pages/Home.jsx';
import Teamoshe from './component/pages/About US/Teamoshe.jsx';
import OrganizationProfile from './component/pages/About US/OrganizationProfile.jsx';
// import MessagefromEd from './component/pages/About US/MessagefromEd.jsx';
import OurMissionandVision from './component/pages/About US/OurMissionandVision.jsx';
// import PartnersandDonors from './component/pages/About US/PartnersandDonors.jsx';
import OSHEStrength from './component/pages/About US/OSHEStrength.jsx';
import OngoingProject from './component/pages/Campaigns & Projects/OngoingProject.jsx';
import PastProject from './component/pages/Campaigns & Projects/PastProject.jsx';
import ProjectPartners from './component/pages/Campaigns & Projects/ProjectPartners.jsx';
import Events from './component/pages/Campaigns & Projects/Events.jsx';
import PressRelease from './component/pages/Media_and_Resource/PressRelease.js';



const router= createBrowserRouter ([
  {
    path:'/',
    Component:Root,
    children:[
      {
        path:'/',
        Component:Home,
      },
      {
        path:'/TeamOshe',
        Component:Teamoshe,
      },
      {
        path:'/OrganizationProfile',
        Component:OrganizationProfile,
      },
      // {
      //   path:'/MessagefromEd',
      //   Component:MessagefromEd,
      // },
      {
        path:'/OurMissionandVision',
        Component:OurMissionandVision,
      },
      // {
      //   path:'/PartnersandDonors',
      //   Component:PartnersandDonors,
      // },
      {
        path:'/OSHEStrength',
        Component:OSHEStrength,
      },
      {
        path:'/OngoingProject',
        Component:OngoingProject
      },
      {
        path:'/PastProject',
        Component:PastProject,
      },
      {
        path:'/ProjectPartners',
        Component:ProjectPartners,
      },
      {
        path:'/Events',
        Component:Events,
      },
      {
       path:'/PressRelease',
       Component:PressRelease,
      },


    ]
  }
])

createRoot(document.getElementById('root')).render(
  <StrictMode>
    <RouterProvider router={router}></RouterProvider>
  </StrictMode>,
)

import Root from '../component/layout/Root';
import MissionVision from '../component/pages/Home/sections/MissionVision';
import React from 'react';
import Breadcrumb from '../component/breadcrumb';


export default function OurMissionandVision() {
  return (
    <Root>
            <Breadcrumb title="Our Mission and Vision" subtitle="Purpose and direction" summary="Understand the mission and long-term vision of OSHE Foundation." />
            <MissionVision />


    </Root>
  )
}


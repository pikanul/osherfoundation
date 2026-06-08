import Root from '../component/layout/Root'
import OngoingProjectContent from '../component/pages/Campaigns & Projects/OngoingProject'
import Breadcrumb from '../component/breadcrumb';

export default function OngoingProject() {
  return (
    <Root>
            <Breadcrumb title="Ongoing Projects" subtitle="Current initiatives in action" summary="See what OSHE Foundation is currently working on." />
      <OngoingProjectContent />
    </Root>
  )
}


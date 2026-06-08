import Root from '../component/layout/Root'
import ProjectPartnersContent from '../component/pages/Campaigns & Projects/ProjectPartners'
import Breadcrumb from '../component/breadcrumb';

export default function ProjectPartners() {
  return (
    <Root>
            <Breadcrumb title="Project Partners" subtitle="Collaboration network" summary="Meet the partners supporting OSHE Foundation projects." />
      <ProjectPartnersContent />
    </Root>
  )
}


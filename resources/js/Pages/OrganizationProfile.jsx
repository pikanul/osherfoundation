import Root from '../component/layout/Root'
import { usePage } from "@inertiajs/react";
import Breadcrumb from '../component/breadcrumb';
export default function OrganizationProfile() {
    const { props } = usePage();
    const { organization_profile_description, organization_profile_title } = props;
    return (
        <Root>
            <Breadcrumb title="Organization Profile" subtitle={organization_profile_title} summary="Learn about OSHE Foundation and our organizational background." />
            <div>
                <div className='max-w-10/12 mx-auto py-10 space-y-4 '>
                    <div className="prose rich-content max-w-none" dangerouslySetInnerHTML={{ __html: organization_profile_description }} />
                </div>
            </div>
        </Root>
    )
}

import Root from '../component/layout/Root'
import  { usePage } from "@inertiajs/react";
import Breadcrumb from '../component/breadcrumb';

export default function OSHEStrength() {
    const { props } = usePage();
    const { strength_description,strength_title } = props;
  return (
    <Root>
            <Breadcrumb title="OSHE Strength" subtitle={strength_title} summary="Explore the capabilities that drive our impact." />
              <div>

            <div  className='max-w-10/12 mx-auto  py-10 space-y-4 font-bold mt-10 mb-10 '>
                <div className="prose rich-content max-w-none" dangerouslySetInnerHTML={{ __html: strength_description }} />
            </div>
        </div>
    </Root>
  )
}

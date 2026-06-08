import Root from '../component/layout/Root'
import { usePage } from "@inertiajs/react";

export default function Page() {

    const { props } = usePage();
    const { page, about_project1, about_project2 } = props;
    const title = page?.name || 'About Project';


    return (
        <Root>

            <div className="bg-gray-100 flex justify-center items-center min-h-screen p-4">
                <div className="w-full max-w-10/12  bg-white rounded-xl shadow-lg overflow-hidden p-8">
                    

                    <img src={about_project1} alt="About Project Image 1" className="mb-6 rounded-lg shadow-md" />
                    <img src={about_project2} alt="About Project Image 2" className="mb-6 rounded-lg shadow-md" />

                </div>
            </div>
        </Root>


    )
}

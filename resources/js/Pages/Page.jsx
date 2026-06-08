import Root from '../component/layout/Root'
import { useState } from 'react';
import { usePage } from "@inertiajs/react";
import Breadcrumb from '../component/breadcrumb';

export default function Page() {

    const { props } = usePage();
    const { page } = props;


    return (
        <Root>
            <Breadcrumb title="OSHE Foundation Page" subtitle="Detailed information" summary="Read more details from this page." />
            <div className="bg-gray-100 flex justify-center  min-h-screen p-4">
                <div className="w-full max-w-10/12  bg-white rounded-xl shadow-lg overflow-hidden p-8">
                    <h1 className="text-3xl font-bold mb-6 text-gray-800 mb-3 text-dark">{page.name}</h1>

                    <div className="prose rich-content max-w-none text-gray-700 break-words" dangerouslySetInnerHTML={{ __html: page.description }} />
                </div>
            </div>
        </Root>


    )
}


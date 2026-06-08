import Root from '../component/layout/Root'
import { Link, usePage } from "@inertiajs/react";
import Breadcrumb from '../component/breadcrumb';

export default function NewsLetter() {
const { props } = usePage();
const { message } = props;
  return (
    <Root>
            <Breadcrumb title="404" subtitle="Page not found" summary="The page you are looking for does not exist." />
      <div className="text-center py-20">
        <h1 className="text-4xl font-bold mb-4">404 - Not Found</h1>
        <p className="text-lg text-gray-600">{message || 'The page you are looking for does not exist.'}</p>
      </div>
    </Root>
  )
}


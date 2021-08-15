/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import {lazy} from "react";

const ListPage = lazy(() => import("@EveryWorkflow/PageBundle/Admin/Page/ListPage"));
const FormPage = lazy(() => import("@EveryWorkflow/PageBundle/Admin/Page/FormPage"));

export const PageRoutes = [
    {
        path: '/cms/page',
        exact: true,
        component: ListPage
    },
    {
        path: '/cms/page/create',
        exact: true,
        component: FormPage
    },
    {
        path: '/cms/page/:uuid/edit',
        exact: true,
        component: FormPage
    },
];

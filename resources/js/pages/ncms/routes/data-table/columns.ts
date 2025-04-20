import { type ColumnDef } from '@tanstack/table-core';
import { default as globalvars } from '../Routes.vue';

const columns: ColumnDef<(typeof globalvars)[0]>[] = [
    {
        accessorKey: 'name',
        header: 'Name',
        cell: ({ row }) => row.original.name,
    },
    {
        accessorKey: 'route',
        header: 'Route',
        cell: ({ row }) => row.original.route,
    },
    {
        accessorKey: 'description',
        header: 'Description',
        cell: ({ row }) => row.original.description,
    },
    {
        id: 'actions',
        enableHiding: false,
        header: 'Actions',
    },
];

export default columns;

import { type ColumnDef } from '@tanstack/table-core';
import { default as globalvars } from '../GlobalVars.vue';

const columns: ColumnDef<(typeof globalvars)[0]>[] = [
    {
        accessorKey: 'name',
        header: 'Name',
        cell: ({ row }) => row.original.name,
    },
    {
        accessorKey: 'value',
        header: 'Value',
        cell: ({ row }) => row.original.value,
    },
    {
        accessorKey: 'description',
        header: 'Description',
        cell: ({ row }) => row.original.description,
    },
    {
        accessorKey: 'is_read_only',
        header: 'Read Only',
        cell: ({ row }) => (row.original.is_read_only ? 'Yes' : 'No'),
    },
    {
        accessorKey: 'is_protected',
        header: 'Protected',
        cell: ({ row }) => (row.original.is_protected ? 'Yes' : 'No'),
    },
    {
        id: 'actions',
        enableHiding: false,
        header: 'Actions',
    },
];

export default columns;

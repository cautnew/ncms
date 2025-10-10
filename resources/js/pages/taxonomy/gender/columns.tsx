import { ColumnDef } from '@tanstack/react-table';

export type Gender = {
    id: number;
    name: string;
    symbol: string;
};

export const columns: ColumnDef<Gender>[] = [
    {
        accessorKey: 'id',
        header: 'ID',
    },
    {
        accessorKey: 'name',
        header: 'Name',
    },
    {
        accessorKey: 'symbol',
        header: 'Symbol',
    },
];

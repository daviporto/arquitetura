import type { Meta, StoryObj } from '@storybook/react';
import { Select, SelectProps } from 'components/Select';

type SelectStoriesProps = {
	story: string;
	book: string;
};

export default {
	title: 'Components/Select',
	component: Select,
	tags: ['autodocs'],
	argTypes: {
		options: {
			description: 'Array of options for the select box',
			control: 'object',
		},
		placeholder: {
			description: 'Placeholder text for the select box',
			control: 'text',
		},
		defaultValue: {
			description: 'Default value for the select box',
			control: 'text',
		},
		register: {
			description: 'Register function for forms (e.g., react-hook-form)',
		},
		requiredMessage: {
			description: 'Message to show when the field is required',
			control: 'text',
		},
	},
} as Meta<SelectProps<SelectStoriesProps>>;

const options = [
	{ value: 'presencial', label: 'Presencial' },
	{ value: 'home-office', label: 'Home Office' },
];

export const TypeWork: StoryObj<SelectProps<SelectStoriesProps>> = {
	args: {
		options: options,
		placeholder: 'Selecione um modelo',
	},
};

const paymentOptions = [
	{ value: 'monthly', label: 'Mensal' },
	{ value: 'weekly', label: 'Semanal' },
];

export const PaymentPeriod: StoryObj<SelectProps<SelectStoriesProps>> = {
	args: {
		options: paymentOptions,
		placeholder: 'Selecione o período de pagamento',
	},
};

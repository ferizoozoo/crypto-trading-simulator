import { describe, test, expect } from 'vitest';
import '@testing-library/jest-dom/vitest';
import { render, screen } from '@testing-library/svelte';
import Page from './+page.svelte';

describe('/+page.svelte', () => {
	test('should render balance display component', () => {
		render(Page);
		expect(screen.getByPlaceholderText('balance-display')).toBeInTheDocument();
	});

	test('should render market data component', () => {
		render(Page);
		expect(screen.getByPlaceholderText('market-data')).toBeInTheDocument();
	});

	test('should render transaction history component', () => {
		render(Page);
		expect(screen.getByPlaceholderText('transaction-history')).toBeInTheDocument();
	});

	test('should buy and sell orders component', () => {
		render(Page);
		expect(screen.getByPlaceholderText('buy-and-sell-orders')).toBeInTheDocument();
	});

	test('should render the user-related part', () => {
		render(Page);
		expect(screen.getByPlaceholderText('user')).toBeInTheDocument();
	});
});

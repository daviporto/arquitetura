/// <reference types="cypress" />
//

beforeEach(() => {
	cy.visit('/', {
		failOnStatusCode: false,
	});
	cy.signIn('candidato@gmail.com', 'testaa');
	cy.waitUntil(() => cy.url().should('contain', 'home'));

	cy.getByDataCy('sidebar').within(() => {
		cy.findByRole('link', { name: /Perfil Público/i }).click();
	});

	cy.waitUntil(() => cy.url().should('contain', 'config'));
});

describe('Config - Professional XP', () => {
	it('should be able to add an xp with end_date', () => {
		cy.createProfessionalXp({
			companyName: 'Google',
			position: 'Software Developer',
			location: 'Vale do Silício',
			workModel: 'Home Office',
			employmentType: 'Tempo integral',
			startDate: '2022-05-11',
			endDate: new Date().toISOString().split('T')[0],
			description:
				'Lorem ipsum dolor sit amet, officia excepteur ex fugiat reprehenderit enim labore culpa sint ad nisi Lorem pariatur mollit ex esse exercitation amet.',
		});

		cy.wait(2000);

		cy.findAllByText('Software Developer');
	});

	it('should be able to add an xp without end_date', () => {
		cy.createProfessionalXp({
			companyName: 'Amazon',
			position: 'AWS Engineer',
			location: 'Vale do Silício',
			workModel: 'Home Office',
			employmentType: 'Tempo integral',
			startDate: '2022-05-11',
			description:
				'Lorem ipsum dolor sit amet, officia excepteur ex fugiat reprehenderit enim labore culpa sint ad nisi Lorem pariatur mollit ex esse exercitation amet.',
		});

		cy.wait(2000);

		cy.findAllByText('AWS Engineer');
	});

	it('should be able to add an xp without end_date by sad path', () => {
		cy.findByTitle('Adicionar Experiência').click();

		cy.getByName('position').focus();

		cy.findByText('O nome da empresa é obrigatório.');

		cy.getByName('location').focus();
		cy.findByText('O cargo é obrigatório.');

		cy.getByName('start_date').focus();
		cy.findByText('A localização é obrigatória.');

		cy.getByName('description', 'textarea').focus();
		cy.findByText('A data de início é obrigatória.');

		cy.getByName('company_name').focus();
		cy.findByText('A descrição é obrigatória.');

		cy.createProfessionalXp(
			{
				companyName: 'Sad Path Job',
				position: 'Software Developer Sad Path',
				location: 'Vale do Silício',
				workModel: 'Presencial',
				employmentType: 'Meio Período',
				startDate: '2022-05-23',
				description:
					'Lorem ipsum dolor sit amet, officia excepteur ex fugiat reprehenderit enim labore culpa sint ad nisi Lorem pariatur mollit ex esse exercitation amet.',
			},
			false,
		);

		cy.wait(2000);

		cy.findAllByText('Software Developer Sad Path');
	});

	it('should be able to add an xp and then remove it', () => {
		cy.createProfessionalXp({
			companyName: 'Experiência a ser removida',
			position: 'Software Developer',
			location: 'Vale do Silício',
			workModel: 'Home Office',
			employmentType: 'Tempo integral',
			startDate: '2022-05-11',
			endDate: new Date().toISOString().split('T')[0],
			description:
				'Lorem ipsum dolor sit amet, officia excepteur ex fugiat reprehenderit enim labore culpa sint ad nisi Lorem pariatur mollit ex esse exercitation amet.',
		});

		cy.wait(2000);

		cy.findAllByText('Software Developer');

		cy.findAllByTitle('Remover experiência').last().click();

		cy.findByText('Tem certeza que deseja excluir essa experiência?');

		cy.findByRole('button', { name: /Cancelar/i }).should('be.enabled');

		cy.findByRole('button', { name: /Excluir/i })
			.should('be.enabled')
			.click();

		cy.findByText('Experiência a ser removida').should('not.exist');
	});
});

<?php

namespace Tests\Feature;

use App\Models\CursoModel;
use App\Models\UserModel;
use CodeIgniter\Shield\Test\AuthenticationTesting;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class CursoControllerTest extends CIUnitTestCase
{
    use AuthenticationTesting;
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $namespace = [
        "App",
        "CodeIgniter\Settings",
        "CodeIgniter\Shield"
    ];

    public function test_route_protection()
    {
        $this->get("/sys/cursos")->assertRedirectTo("/login");
        $this->post("/sys/cursos/create")->assertRedirectTo("/login");
        $this->post("/sys/cursos/update")->assertRedirectTo("/login");
        $this->post("/sys/cursos/delete")->assertRedirectTo("/login");
    }

    public function test_create_curso()
    {
        $result = $this->actingAs(fake(UserModel::class))->post("/sys/cursos/create", [
            "nome" => "Física"
        ]);

        $result->assertRedirectTo("/sys/cursos");

        $result->assertSessionHas("sucesso", "Curso cadastrado com sucesso!");
    }

    public function test_do_not_create_duplicate()
    {
        $this->actingAs(fake(UserModel::class));

        $curso = fake(CursoModel::class);

        $this->post("/sys/cursos/create", $curso)->assertOk();

        $result = $this->post("/sys/cursos/create", $curso);

        $result->assertRedirectTo("/sys/cursos");

        $this->assertContains(
            "Ocorreu um erro ao cadastrar o curso!",
            session()->getFlashdata("erros")
        );
    }

    public function test_create_curso_without_nome()
    {
        $result = $this->actingAs(fake(UserModel::class))->post("/sys/cursos/create", [
            "nome" => "" // campo vazio
        ]);

        $result->assertRedirectTo("/sys/cursos");

        $this->assertContains(
            "Informe o nome da Curso.",
            session()->getFlashdata("erros")
        );
    }

    public function test_update_curso()
    {
        $curso = fake(CursoModel::class);

        $this->actingAs(fake(UserModel::class))
            ->post("/sys/cursos/create", $curso)
            ->assertOk();

        $curso["nome"] = "Física Atualizada";

        $result = $this->post("/sys/cursos/update", [
            "id" => $curso["id"],
            "nome" => $curso["nome"]
        ]);

        $result->assertRedirectTo("/sys/cursos");
        $result->assertSessionHas("sucesso", "Curso atualizado com sucesso!");
    }

    public function test_delete_curso()
    {
        $curso = fake(CursoModel::class);

        $this->actingAs(fake(UserModel::class))
            ->post("/sys/cursos/create", $curso)
            ->assertOk();

        $result = $this->post("/sys/cursos/delete", [
            "id" => $curso["id"]
        ]);

        $result->assertRedirectTo("/sys/cursos");
        $result->assertSessionHas("sucesso", "Curso deletado com sucesso!");
    }

    public function test_show_curso_list_as_authenticated_user()
    {
        $result = $this->actingAs(fake(UserModel::class))
        ->get("/sys/cursos");

        $result->assertOk();
    }
}

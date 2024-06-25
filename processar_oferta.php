<?php
session_start();
require_once './classes/db_connect.php';

// Initialize response array
$response = [
    'success' => false,
    'message' => 'Unknown error occurred.'
];

// Check if the user is logged in
if (!isset($_SESSION["logado"])) {
    http_response_code(401); // Unauthorized
    $response['message'] = 'Acesso não autorizado.';
    echo json_encode($response);
    exit;
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method not allowed
    $response['message'] = 'Método não permitido.';
    echo json_encode($response);
    exit;
}

// Check if required POST variables are set
if (!isset($_POST['action'], $_POST['interesse_id'], $_POST['produto_id'])) {
    http_response_code(400); // Bad request
    $response['message'] = 'Parâmetros insuficientes.';
    echo json_encode($response);
    exit;
}

// Extract variables from POST
$action = $_POST['action'];
$interesseId = $_POST['interesse_id'];
$produtoId = $_POST['produto_id'];

// Actions handling
switch ($action) {
    case 'aceitar':
        $response = acceptOffer($pdo, $interesseId);
        break;

    case 'recusar':
        $response = refuseOffer($pdo, $interesseId);
        break;

    case 'propor':
        if (!isset($_POST['novo_produto_id'], $_POST['nova_proposta'])) {
            http_response_code(400); // Bad request
            $response['message'] = 'Parâmetros insuficientes para nova proposta.';
            echo json_encode($response);
            exit;
        }
        $novoProdutoId = $_POST['novo_produto_id'];
        $novaProposta = $_POST['nova_proposta'];
        $response = proposeNewOffer($pdo, $produtoId, $_SESSION['usuario_id'], $novoProdutoId, $novaProposta, $interesseId);
        break;

    default:
        http_response_code(400); // Bad request
        $response['message'] = 'Ação não reconhecida.';
        break;
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit;

// Function definitions
function acceptOffer($pdo, $interesseId)
{
    try {
        $pdo->beginTransaction();

        $sqlSelectProdutosTroca = "SELECT produto_id, produto_ofertado_id FROM interesses WHERE id = ?";
        $stmtSelectProdutosTroca = $pdo->prepare($sqlSelectProdutosTroca);
        $stmtSelectProdutosTroca->execute([$interesseId]);
        $produtosTroca = $stmtSelectProdutosTroca->fetch(PDO::FETCH_ASSOC);

        $sqlUpdateProdutos = "UPDATE produtos SET estado = 'trocado' WHERE id = ?";
        $stmtUpdateProduto1 = $pdo->prepare($sqlUpdateProdutos);
        $stmtUpdateProduto1->execute([$produtosTroca['produto_id']]);
        $stmtUpdateProduto2 = $pdo->prepare($sqlUpdateProdutos);
        $stmtUpdateProduto2->execute([$produtosTroca['produto_ofertado_id']]);

        $sqlRemoverInteresse = "DELETE FROM interesses WHERE id = ?";
        $stmtRemoverInteresse = $pdo->prepare($sqlRemoverInteresse);
        $stmtRemoverInteresse->execute([$interesseId]);

        $pdo->commit();
        return ['success' => true, 'message' => 'Oferta aceita com sucesso!'];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'message' => 'Erro ao processar a aceitação da oferta: ' . $e->getMessage()];
    }
}

function refuseOffer($pdo, $interesseId)
{
    try {
        $sqlRemoverInteresse = "DELETE FROM interesses WHERE id = ?";
        $stmtRemoverInteresse = $pdo->prepare($sqlRemoverInteresse);
        $stmtRemoverInteresse->execute([$interesseId]);
        return ['success' => true, 'message' => 'Oferta recusada com sucesso!'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Erro ao recusar a oferta: ' . $e->getMessage()];
    }
}

function proposeNewOffer($pdo, $produtoId, $userId, $novoProdutoId, $novaProposta, $originalId)
{
    try {
        $pdo->beginTransaction();
        $sqlInserirProposta = "INSERT INTO interesses (produto_id, interessado_id, produto_ofertado_id, proposta, status, proposta_original_id) VALUES (?, ?, ?, ?, 'pendente', ?)";
        $stmtInserirProposta = $pdo->prepare($sqlInserirProposta);
        $stmtInserirProposta->execute([$novoProdutoId, $userId, $produtoId, $novaProposta, $originalId]);
        $sqlRemoverInteresse = "DELETE FROM interesses WHERE id = ?";
        $stmtRemoverInteresse = $pdo->prepare($sqlRemoverInteresse);
        $stmtRemoverInteresse->execute([$originalId]);
        $pdo->commit();
        return ['success' => true, 'message' => 'Nova proposta enviada com sucesso!'];
    } catch (Exception $e) {
        $pdo->rollback();   
        return ['success' => false, 'message' => 'Erro ao enviar nova proposta: ' . $e->getMessage()];
    }

}

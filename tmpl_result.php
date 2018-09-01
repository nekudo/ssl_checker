<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSL Certificate Info</title>
    <link rel="stylesheet" href="picnic.min.css"></head>
    <style>.container { width: 1200px; margin: auto; }</style>
<body>
    <div class="container">
        <h1>SSL Certificate Information</h1>
        <table class="pure-table pure-table-bordered">
            <thead>
                <tr>
                    <th>Domain</th>
                    <th>Issuer</th>
                    <th>Valid From</th>
                    <th>Valid To</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($this->certificateData as $item): ?>
                    <tr>
                        <td><?php echo $item['domain']; ?></td>
                        <td><?php echo $item['issuer']; ?></td>
                        <td><?php echo $item['valid_from']; ?></td>
                        <td><?php echo $item['valid_to']; ?></td>
                        <td>
                            <?php if ($item['state'] === 'error'): ?>
                                <span class="label error">Error</span>
                            <?php elseif ($item['state'] === 'warning'): ?>
                                <span class="label warning">Expiering Soon</span>
                            <?php else: ?>
                                <span class="label success">OK</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
